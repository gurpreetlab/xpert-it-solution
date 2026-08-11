<?php

namespace Tests\Feature\Shop;

use App\Livewire\Shop\Wishlist;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles/permissions so we can assign customer role
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_guests_cannot_access_wishlist_page(): void
    {
        $response = $this->get(route('shop.wishlist'));
        $response->assertRedirect(route('login'));
    }

    public function test_customers_can_render_wishlist_page(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($customer);

        $response = $this->get(route('shop.wishlist'));
        $response->assertOk();
    }

    public function test_customers_can_add_and_remove_items_from_wishlist(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $category = Category::create(['name' => 'Networking']);
        $brand = Brand::create(['name' => 'Netgear']);

        $product = Product::create([
            'name' => 'Netgear Wi-Fi Router',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 5000,
            'mrp' => 6000,
            'stock' => 10,
        ]);

        $this->actingAs($customer);

        // Initially wishlist is empty
        $this->assertCount(0, $customer->wishlistProducts);

        // Verify WishlistCount component is 0 initially
        Livewire::test(\App\Livewire\Shop\Partials\WishlistCount::class)
            ->assertSet('count', 0);

        // Toggle / Add product to wishlist
        Livewire::test(\App\Livewire\Shop\ProductDetail::class, ['slug' => $product->slug])
            ->call('toggleWishlist')
            ->assertDispatched('wishlist-updated');

        $this->assertCount(1, $customer->fresh()->wishlistProducts);
        $this->assertTrue($customer->fresh()->wishlistProducts->contains($product->id));

        // Verify WishlistCount component is 1
        Livewire::test(\App\Livewire\Shop\Partials\WishlistCount::class)
            ->assertSet('count', 1);

        // Toggle again to remove
        Livewire::test(\App\Livewire\Shop\ProductDetail::class, ['slug' => $product->slug])
            ->call('toggleWishlist')
            ->assertDispatched('wishlist-updated');

        $this->assertCount(0, $customer->fresh()->wishlistProducts);

        // Verify WishlistCount component is 0 again
        Livewire::test(\App\Livewire\Shop\Partials\WishlistCount::class)
            ->assertSet('count', 0);
    }

    public function test_customers_can_add_wishlisted_product_to_cart(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $category = Category::create(['name' => 'Networking']);
        $brand = Brand::create(['name' => 'Netgear']);

        $product = Product::create([
            'name' => 'Netgear Wi-Fi Router',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 5000,
            'mrp' => 6000,
            'stock' => 10,
        ]);

        // Attach product to user's wishlist
        $customer->wishlistProducts()->attach($product->id);

        $this->actingAs($customer);

        // Cart should be empty initially
        $this->assertNull($customer->cart);

        // Click Add to Cart inside Wishlist component
        Livewire::test(Wishlist::class)
            ->call('addToCart', $product->id)
            ->assertDispatched('cart-updated');

        $this->assertNotNull($customer->fresh()->cart);
        $this->assertCount(1, $customer->fresh()->cart->items);
        $this->assertEquals($product->id, $customer->fresh()->cart->items->first()->product_id);
    }
}
