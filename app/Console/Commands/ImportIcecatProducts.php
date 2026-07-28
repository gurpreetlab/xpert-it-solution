<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Services\IcecatService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Signature("icecat:import {--category= : Target category slug to map imported products to} {--file= : Path to a text file containing line-separated GTINs/MPNs to import}")]
#[Description("Selective product importer from Icecat")]
class ImportIcecatProducts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(IcecatService $icecatService)
    {
        $filePath = $this->option('file');
        $categorySlug = $this->option('category');

        if (!$filePath || !file_exists($filePath)) {
            $this->error("Please provide a valid text file containing SKUs/GTINs using --file=path/to/list.txt");
            return 1;
        }

        if (!$categorySlug) {
            $this->error("Please specify a target category slug using --category=category-slug");
            return 1;
        }

        $category = Category::where('slug', $categorySlug)->first();
        if (!$category) {
            $this->error("Category with slug '{$categorySlug}' not found in database. Create it first.");
            return 1;
        }

        // Lines are either a bare GTIN, or "ProductCode,BrandName" - Icecat
        // requires the brand whenever you're not searching by GTIN.
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $total = count($lines);

        $this->info("Starting import of {$total} items into category: {$category->name}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $imported = 0;
        $skipped = 0;

        foreach ($lines as $line) {
            [$searchTerm, $brandName] = array_pad(explode(',', trim($line), 2), 2, '');
            $searchTerm = trim($searchTerm);
            $brandName = trim($brandName);

            try {
                $productData = $icecatService->getProductByEanOrMpn($searchTerm, $brandName);

                if (!$productData) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                if ($this->storeProduct($productData, $category)) {
                    $imported++;
                } else {
                    $skipped++;
                }
            } catch (\Throwable $e) {
                // Log the failure per item to keep the bulk import moving.
                report($e);
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Import completed: {$imported} imported/updated, {$skipped} skipped.");

        return 0;
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

        DB::transaction(function () use ($productData, $generalInfo, $icecatId, $category) {
            // 1. Brand - match on Icecat's brand id first so the same brand
            //    never gets created twice under slightly different casings.
            $brandId = null;
            $supplierName = $generalInfo['Brand'] ?? null;
            $icecatBrandId = $generalInfo['BrandID'] ?? null;

            if (!empty($icecatBrandId)) {
                $brand = Brand::updateOrCreate(
                    ['icecat_brand_id' => (int) $icecatBrandId],
                    [
                        'name' => $supplierName ?: 'Unknown Brand',
                        'slug' => Str::slug($supplierName ?: 'brand-' . $icecatBrandId),
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

            // 2. Basic details - field names per Icecat's documented
            //    GeneralInfo schema (BrandPartCode is the MPN/product code,
            //    "ProductCode" is only the name used in the granular call).
            $name = $generalInfo['Title'] ?? $generalInfo['ProductName'] ?? 'Unknown Product';
            $mpn = $generalInfo['BrandPartCode'] ?? null;
            $gtin = $generalInfo['GTIN'][0] ?? null;

            $longDesc = $generalInfo['Description']['LongDesc'] ?? null;
            $shortDesc = $generalInfo['SummaryDescription']['ShortSummaryDescription']
                ?? $generalInfo['Description']['MiddleDesc']
                ?? null;

            // 3. Upsert the product, keyed on the Icecat product id.
            $product = Product::updateOrCreate(
                ['icecat_id' => $icecatId],
                [
                    'category_id' => $category->id,
                    'brand_id' => $brandId,
                    'name' => $name,
                    'slug' => Str::slug($name . '-' . ($mpn ?: $icecatId)),
                    'sku' => $mpn ?: ('ICECAT-' . $icecatId),
                    'mpn' => $mpn,
                    'gtin' => $gtin,
                    'short_description' => $shortDesc,
                    'description' => $longDesc,
                    'icecat_synced_at' => now(),
                    'is_active' => true,
                ]
            );

            // 4. Images - "Gallery" is a SIBLING of "GeneralInfo" at the top
            //    level of the response, not nested inside "Image". The main
            //    image is duplicated inside Gallery and flagged IsMain=Y.
            $gallery = $productData['Gallery'] ?? [];
            if (!empty($gallery)) {
                $imageInserts = [];
                foreach ($gallery as $idx => $img) {
                    $path = $img['Pic'] ?? $img['HighPic'] ?? '';
                    if ($path === '') {
                        continue;
                    }

                    $isMain = ($img['IsMain'] ?? 'N') === 'Y';
                    $imageInserts[] = [
                        'product_id' => $product->id,
                        'path' => $path,
                        'source' => 'icecat',
                        'type' => $isMain ? 'main' : 'gallery',
                        'is_primary' => $isMain,
                        'sort_order' => (int) ($img['No'] ?? $idx),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($imageInserts)) {
                    ProductImage::where('product_id', $product->id)->delete();
                    ProductImage::insert($imageInserts);
                }
            }

            // 5. Specifications - the feature's name/id live under the
            //    nested "Feature" object, and the group name under
            //    "FeatureGroup" - not directly on the feature/group.
            $featureGroups = $productData['FeaturesGroups'] ?? [];
            if (!empty($featureGroups)) {
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

                if (!empty($specInserts)) {
                    ProductSpecification::where('product_id', $product->id)->delete();
                    // Chunk inserts to handle large specification arrays.
                    foreach (array_chunk($specInserts, 100) as $chunk) {
                        ProductSpecification::insert($chunk);
                    }
                }
            }
        });

        return true;
    }
}