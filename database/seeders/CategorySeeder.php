<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Networking',
            'CCTV & Security',
            'Storage',
            'Computer Peripherals',
            'Power & Accessories',
            'Printing',
        ];

        DB::transaction(function () use ($categories) {
            foreach ($categories as $category) {
                Category::firstOrCreate(['name' => $category]);
            }
        }, attempts: 5);
    }
}
