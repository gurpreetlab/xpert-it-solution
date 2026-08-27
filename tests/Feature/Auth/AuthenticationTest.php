<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('home', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_super_admin_redirects_to_dashboard_on_login(): void
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super-admin']);
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrorsIn('email');

        $this->assertGuest();
    }

    public function test_users_with_two_factor_enabled_are_redirected_to_two_factor_challenge(): void
    {
        $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]);

        $user = User::factory()->withTwoFactor()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('two-factor.login'));
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_guest_cart_and_wishlist_are_merged_upon_login(): void
    {
        $category = \App\Models\Category::create(['name' => 'Networking']);
        $brand = \App\Models\Brand::create(['name' => 'Cisco']);
        $product = \App\Models\Product::create([
            'name' => 'Cisco Catalyst Switch',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 25000,
            'mrp' => 30000,
            'stock' => 10,
        ]);

        // Guest adds product to session cart and wishlist
        \App\Support\CartManager::add($product->id, 2);
        \App\Support\WishlistManager::toggle($product->id);

        $this->assertEquals(2, \App\Support\CartManager::count());
        $this->assertEquals(1, \App\Support\WishlistManager::count());

        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        // Verify items were merged into user's DB cart & wishlist
        $this->assertEquals(2, \App\Support\CartManager::count());
        $this->assertEquals(1, \App\Support\WishlistManager::count());
        $this->assertTrue($user->fresh()->wishlistProducts->contains($product->id));
        $this->assertNotNull($user->fresh()->cart);
        $this->assertEquals(2, $user->fresh()->cart->items->first()->quantity);
    }
}
