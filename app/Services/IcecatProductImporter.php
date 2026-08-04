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
        $product = DB::transaction(function () use ($generalInfo, $icecatId, $category) {
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
            $gtin = $generalInfo['GTIN'][0] ?? null;

            $longDesc = $generalInfo['Description']['LongDesc'] ?? null;
            $shortDesc = $generalInfo['SummaryDescription']['ShortSummaryDescription']
                ?? $generalInfo['Description']['MiddleDesc']
                ?? null;

            return Product::updateOrCreate(
                ['icecat_id' => $icecatId],
                [
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'name' => $name,
                    'slug' => Str::slug($name.'-'.($mpn ?: $icecatId)),
                    'sku' => $mpn ?: ('ICECAT-'.$icecatId),
                    'mpn' => $mpn,
                    'gtin' => $gtin,
                    'short_description' => $shortDesc,
                    'description' => $longDesc,
                    'icecat_synced_at' => now(),
                    'is_active' => true,
                ]
            );
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
        $featureGroups = $productData['FeaturesGroups'] ?? [];
        if (! empty($featureGroups)) {
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

            if (! empty($specInserts)) {
                ProductSpecification::where('product_id', $product->id)->delete();
                // Chunk inserts to handle large specification arrays.
                foreach (array_chunk($specInserts, 100) as $chunk) {
                    ProductSpecification::insert($chunk);
                }
            }
        }

        return true;
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
            $url = $img['Pic'] ?? $img['HighPic'] ?? '';
            if ($url === '') {
                continue;
            }

            $candidates[] = [
                'url' => $url,
                'is_main' => ($img['IsMain'] ?? 'N') === 'Y',
                'sort_order' => (int) ($img['No'] ?? $idx),
            ];
        }

        if (empty($candidates)) {
            return [];
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
