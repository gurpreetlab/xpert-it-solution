<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Services\IcecatProductImporter;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature("icecat:import {--category= : Target category slug to map imported products to} {--file= : Path to a text file containing line-separated GTINs/MPNs to import}")]
#[Description("Selective product importer from Icecat")]
class ImportIcecatProducts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(IcecatProductImporter $importer)
    {
        $filePath = $this->option('file');
        $categorySlug = $this->option('category');

        if (! $filePath || ! file_exists($filePath)) {
            $this->error("Please provide a valid text file containing SKUs/GTINs using --file=path/to/list.txt");

            return 1;
        }

        if (! $categorySlug) {
            $this->error("Please specify a target category slug using --category=category-slug");

            return 1;
        }

        $category = Category::where('slug', $categorySlug)->first();
        if (! $category) {
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

        $result = $importer->importLines($lines, $category, function () use ($bar) {
            $bar->advance();
        });

        $bar->finish();
        $this->newLine();
        $this->info("Import completed: {$result['imported']} imported/updated, {$result['skipped']} skipped.");

        foreach ($result['failures'] as $failure) {
            $this->warn("  Skipped '{$failure['term']}': {$failure['reason']}");
        }

        return 0;
    }
}