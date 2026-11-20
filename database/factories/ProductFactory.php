<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mrp = fake()->numberBetween(1000, 100000);

        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->words(3, true),
            'sku' => fake()->unique()->bothify('SKU-####-????'),
            'mrp' => $mrp,
            'sale_price' => (int) round($mrp * 0.9),
            'purchase_price' => (int) round($mrp * 0.75),
            'stock' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the product was imported from Icecat.
     */
    public function fromIcecat(?int $icecatId = null): static
    {
        return $this->state(fn (): array => [
            'icecat_id' => $icecatId ?? fake()->unique()->numberBetween(1000, 99999999),
        ]);
    }

    /**
     * Indicate that the product is hidden from the storefront.
     */
    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
