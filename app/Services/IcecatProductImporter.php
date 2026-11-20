<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IcecatProductImporter
{
    public function __construct(protected IcecatService $icecatService) {}

    /**
     * Imports a list of "GTIN" or "ProductCode,Brand" lines into the given
     * category. Returns ['imported' => int, 'skipped' => int, 'failures' => array].
     *
     * $onProgress, if given, is called after every line with the running
     * totals so a caller (console progress bar, queued job updating a DB
     * row) can report live progress without this method knowing which.
     */
    public function importLines(array $lines, Category $category, ?callable $onProgress = null): array
    {
        $imported = 0;
        $skipped = 0;
        $failures = [];

        foreach ($lines as $line) {
            [$searchTerm, $brandName] = array_pad(explode(',', trim($line), 2), 2, '');
            $searchTerm = trim($searchTerm);
            $brandName = trim($brandName);

            if ($searchTerm === '') {
                continue;
            }

            try {
                $productData = $this->icecatService->getProductByEanOrMpn($searchTerm, $brandName);

                if (! $productData) {
                    $skipped++;
                    $failures[] = ['term' => $searchTerm, 'reason' => 'Not found on Icecat, or brand missing for a product-code lookup.'];
                } elseif ($this->storeProduct($productData, $category)) {
                    $imported++;
                } else {
                    $skipped++;
                    $failures[] = ['term' => $searchTerm, 'reason' => 'Response had no usable Icecat product ID.'];
                }
            } catch (\Throwable $e) {
                report($e);
                $skipped++;
                $failures[] = ['term' => $searchTerm, 'reason' => 'Unexpected error: '.$e->getMessage()];
            }

            if ($onProgress) {
                $onProgress($imported, $skipped, $failures);
            }
        }

        return ['imported' => $imported, 'skipped' => $skipped, 'failures' => $failures];
    }

    /**
     * Persist a single Icecat product payload as a Product + images + specs.
     */
    protected function storeProduct(array $productData, Category $category): bool
    {
        $generalInfo = $productData['GeneralInfo'] ?? [];
        $icecatId = $generalInfo['IcecatId'] ?? null;

        if (empty($icecatId)) {
            // Without a reliable Icecat ID we can't safely upsert - matching
            // on a null icecat_id would silently overwrite an unrelated row.
            report(new \RuntimeException('Icecat import: response had no GeneralInfo.IcecatId, skipping.'));

            return false;
        }

        // 1. Brand + product upsert — kept in its own short transaction.
        // Image downloads happen *outside* any transaction below: they're
        // network I/O and have no business holding a DB lock while they run.
        $featureGroups = $productData['FeaturesGroups'] ?? [];

        $product = DB::transaction(function () use ($generalInfo, $icecatId, $category, $featureGroups) {
            $brandId = null;
            $supplierName = $generalInfo['Brand'] ?? null;
            $icecatBrandId = $generalInfo['BrandID'] ?? null;

            if (! empty($icecatBrandId)) {
                $brand = Brand::updateOrCreate(
                    ['icecat_brand_id' => (int) $icecatBrandId],
                    [
                        'name' => $supplierName ?: 'Unknown Brand',
                        'slug' => Str::slug($supplierName ?: 'brand-'.$icecatBrandId),
                    ]
                );
                $brandId = $brand->id;
            } elseif ($supplierName) {
                $brand = Brand::firstOrCreate(
                    ['name' => $supplierName],
                    ['slug' => Str::slug($supplierName)]
                );
                $brandId = $brand->id;
            }

            // Field names per Icecat's documented GeneralInfo schema
            // (BrandPartCode is the MPN/product code, "ProductCode" is
            // only the name used in the granular call).
            $name = $generalInfo['Title'] ?? $generalInfo['ProductName'] ?? 'Unknown Product';
            $mpn = $generalInfo['BrandPartCode'] ?? null;
            $gtin = $this->extractGtin($generalInfo);
            $longDesc = $this->extractDescription($generalInfo);
            $shortDesc = $this->extractShortDescription($generalInfo);

            $product = Product::firstOrNew(['icecat_id' => $icecatId]);

            $product->fill(array_filter([
                'category_id' => $category->id,
                'brand_id' => $brandId,
                'name' => $name,
                'slug' => Str::slug($name.'-'.($mpn ?: $icecatId)),
                'sku' => $mpn ?: ('ICECAT-'.$icecatId),
                'mpn' => $mpn,
                'gtin' => $gtin,
                'short_description' => $shortDesc,
                'description' => $longDesc,
                'weight' => $this->extractWeightInKg($featureGroups),
                'warranty' => $this->extractWarranty($featureGroups),
                'icecat_synced_at' => now(),
            ], fn ($value): bool => $value !== null));

            // Icecat carries no pricing, so a brand-new product would go live
            // at ₹0. New imports start as drafts; re-syncs keep the admin's
            // existing visibility choice.
            if (! $product->exists) {
                $product->is_active = false;
            }

            $product->save();

            return $product;
        });

        // 2. Images - "Gallery" is a SIBLING of "GeneralInfo" at the top
        //    level of the response, not nested inside "Image". The main
        //    image is duplicated inside Gallery and flagged IsMain=Y.
        //    Downloaded concurrently (see downloadGalleryImages) rather
        //    than one-by-one, since each is an independent network call.
        $gallery = $productData['Gallery'] ?? [];
        if (! empty($gallery)) {
            $imageInserts = $this->downloadGalleryImages($gallery, $product);

            if (! empty($imageInserts)) {
                ProductImage::where('product_id', $product->id)->delete();
                ProductImage::insert($imageInserts);
            }
        }

        // 3. Specifications - the feature's name/id live under the
        //    nested "Feature" object, and the group name under
        //    "FeatureGroup" - not directly on the feature/group.
        $this->replaceSpecifications($featureGroups, $product);

        return true;
    }

    /**
     * Replace a product's specifications with Icecat's feature list.
     *
     * Feature names/ids live under the nested "Feature" object and the
     * group name under "FeatureGroup" — not directly on the feature/group.
     *
     * @param  array<int, array<string, mixed>>  $featureGroups
     */
    protected function replaceSpecifications(array $featureGroups, Product $product): void
    {
        $specInserts = [];

        foreach ($featureGroups as $group) {
            $groupName = $group['FeatureGroup']['Name']['Value'] ?? 'General';

            foreach ($group['Features'] ?? [] as $feature) {
                $specInserts[] = [
                    'product_id' => $product->id,
                    'group_name' => $groupName,
                    'key' => $feature['Feature']['Name']['Value'] ?? 'Feature',
                    'value' => $feature['PresentationValue'] ?? $feature['Value'] ?? '',
                    'unit' => $feature['Feature']['Measure']['Signs']['_'] ?? null,
                    'icecat_feature_id' => $feature['Feature']['ID'] ?? null,
                    'sort_order' => (int) ($feature['SortNo'] ?? 0),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($specInserts === []) {
            return;
        }

        ProductSpecification::where('product_id', $product->id)->delete();

        // Chunk inserts to handle large specification arrays.
        foreach (array_chunk($specInserts, 100) as $chunk) {
            ProductSpecification::insert($chunk);
        }
    }

    /**
     * First barcode Icecat lists for the product.
     *
     * @param  array<string, mixed>  $generalInfo
     */
    protected function extractGtin(array $generalInfo): ?string
    {
        $gtin = trim((string) ($generalInfo['GTIN'][0] ?? ''));

        return $gtin !== '' ? $gtin : null;
    }

    /**
     * Full description, falling back to Icecat's middle description and then
     * its long marketing summary when the manufacturer text is empty.
     *
     * @param  array<string, mixed>  $generalInfo
     */
    protected function extractDescription(array $generalInfo): ?string
    {
        return $this->firstFilled([
            $generalInfo['Description']['LongDesc'] ?? null,
            $generalInfo['Description']['MiddleDesc'] ?? null,
            $generalInfo['SummaryDescription']['LongSummaryDescription'] ?? null,
        ]);
    }

    /**
     * One-line summary for listings.
     *
     * @param  array<string, mixed>  $generalInfo
     */
    protected function extractShortDescription(array $generalInfo): ?string
    {
        return $this->firstFilled([
            $generalInfo['SummaryDescription']['ShortSummaryDescription'] ?? null,
            $generalInfo['Description']['MiddleDesc'] ?? null,
        ]);
    }

    /**
     * @param  list<mixed>  $values
     */
    protected function firstFilled(array $values): ?string
    {
        foreach ($values as $value) {
            $value = trim((string) $value);

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    /**
     * Product weight in kilograms from Icecat's "Weight" feature, falling
     * back to "Package weight". Icecat reports the raw value in the unit
     * given by the feature's measure sign (usually grams).
     *
     * @param  array<int, array<string, mixed>>  $featureGroups
     */
    protected function extractWeightInKg(array $featureGroups): ?float
    {
        foreach (['Weight', 'Package weight', 'Package net weight'] as $featureName) {
            $feature = $this->findFeature($featureGroups, $featureName);

            if ($feature === null || ! is_numeric($feature['Value'] ?? null)) {
                continue;
            }

            $value = (float) $feature['Value'];
            $unit = strtolower((string) ($feature['Feature']['Measure']['Signs']['_'] ?? ''));

            $kilograms = match ($unit) {
                'g' => $value / 1000,
                'kg' => $value,
                default => null,
            };

            if ($kilograms !== null && $kilograms > 0) {
                return round($kilograms, 3);
            }
        }

        return null;
    }

    /**
     * Human-readable warranty period (e.g. "3 year(s)") when Icecat lists one.
     *
     * @param  array<int, array<string, mixed>>  $featureGroups
     */
    protected function extractWarranty(array $featureGroups): ?string
    {
        $feature = $this->findFeature($featureGroups, 'Warranty period');
        $warranty = trim((string) ($feature['PresentationValue'] ?? ''));

        if ($warranty !== '') {
            return $warranty;
        }

        $text = (string) ($this->findFeature($featureGroups, 'Manufacturer warranty')['PresentationValue'] ?? '');

        return $this->singleWarrantyPeriod($text);
    }

    /**
     * Extract "N year(s)" from free-text manufacturer warranty wording, but
     * only when exactly one distinct year count is mentioned — wording like
     * "1-year and 3-year ... depending on country" is left for a human.
     */
    protected function singleWarrantyPeriod(string $text): ?string
    {
        if (preg_match_all('/\b(\d+)[\s-]+years?\b/i', $text, $matches) === 0) {
            return null;
        }

        $years = array_unique($matches[1]);

        return count($years) === 1 ? reset($years).' year(s)' : null;
    }

    /**
     * Find a feature by its English name across all feature groups.
     *
     * @param  array<int, array<string, mixed>>  $featureGroups
     * @return array<string, mixed>|null
     */
    protected function findFeature(array $featureGroups, string $name): ?array
    {
        foreach ($featureGroups as $group) {
            foreach ($group['Features'] ?? [] as $feature) {
                if (strcasecmp((string) ($feature['Feature']['Name']['Value'] ?? ''), $name) === 0) {
                    return $feature;
                }
            }
        }

        return null;
    }

    /**
     * Downloads every gallery image for a product concurrently (via
     * Http::pool) instead of sequentially, and saves each to local
     * storage. Each image is an independent HTTP round-trip to Icecat's
     * CDN, so there's no reason to wait for one before starting the next.
     *
     * Returns rows ready for ProductImage::insert(), with `path` pointing
     * at the local storage file — never the remote Icecat URL — so the
     * rest of the app's asset('storage/'.$path) calls work unchanged.
     */
    protected function downloadGalleryImages(array $gallery, Product $product): array
    {
        $candidates = [];
        foreach ($gallery as $idx => $img) {
            // 500x500 is plenty for the storefront; the full-size originals
            // are often several MB each and make bulk imports slow and huge.
            // Icecat sends missing sizes as empty strings, not absent keys.
            $url = $this->firstFilled([$img['Pic500x500'] ?? null, $img['Pic'] ?? null, $img['HighPic'] ?? null]);
            if ($url === null) {
                continue;
            }

            $candidates[] = [
                'url' => $url,
                'is_main' => ($img['IsMain'] ?? 'N') === 'Y',
                'position' => (int) ($img['No'] ?? $idx),
            ];
        }

        if (empty($candidates)) {
            return [];
        }

        // Main image first, then Icecat's own order, capped to keep storage sane.
        usort($candidates, fn (array $a, array $b): int => [$b['is_main'], $a['position']] <=> [$a['is_main'], $b['position']]);
        $candidates = array_slice($candidates, 0, max(1, (int) config('icecat.max_images', 8)));

        foreach ($candidates as $index => $candidate) {
            $candidates[$index]['sort_order'] = $index;
        }

        /** @var array<int, Response|\Throwable> $responses */
        $responses = Http::pool(fn (Pool $pool) => collect($candidates)
            ->map(fn ($candidate, $key) => $pool->as($key)->timeout(20)->retry(2, 200)->get($candidate['url']))
            ->all()
        );

        $inserts = [];
        $directory = "products/{$product->id}";

        foreach ($candidates as $key => $candidate) {
            $response = $responses[$key] ?? null;

            if (! $response instanceof Response || $response->failed()) {
                report(new \RuntimeException("Icecat import: failed to download image for product #{$product->id}: {$candidate['url']}"));

                continue;
            }

            $extension = strtolower(pathinfo(parse_url($candidate['url'], PHP_URL_PATH) ?? '', PATHINFO_EXTENSION)) ?: 'jpg';
            $relativePath = "{$directory}/".(string) Str::uuid().'.'.$extension;

            Storage::disk('public')->put($relativePath, $response->body());

            $inserts[] = [
                'product_id' => $product->id,
                'path' => $relativePath,
                'source_url' => $candidate['url'],
                'source' => 'icecat',
                'type' => $candidate['is_main'] ? 'main' : 'gallery',
                'is_primary' => $candidate['is_main'],
                'sort_order' => $candidate['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $inserts;
    }
}
