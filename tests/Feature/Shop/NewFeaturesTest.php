<?php

namespace Tests\Feature\Shop;

use App\Livewire\Shop\Checkout;
use App\Livewire\Shop\Compare;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use App\Notifications\AdminNewOrderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_product_card_ratings_rendered_successfully(): void
    {
        $category = Category::create(['name' => 'Networking']);
        $brand = Brand::create(['name' => 'Netgear']);
        $product = Product::create([
            'name' => 'Rated Product',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 500,
        ]);

        $customer = User::factory()->create();
        $product->reviews()->create([
            'user_id' => $customer->id,
            'rating' => 4,
            'comment' => 'This is a fantastic product! Highly recommended.',
        ]);

        $view = $this->view('components.shop.product-card', ['product' => $product]);
        $view->assertSee('4'); // avg rating
        $view->assertSee('(1)'); // count of reviews
    }

    public function test_coupon_discount_applied_on_checkout(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $category = Category::create(['name' => 'Networking']);
        $brand = Brand::create(['name' => 'Netgear']);
        $product = Product::create([
            'name' => 'Discountable Product',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 1000,
            'stock' => 10,
        ]);

        // Add to cart
        $cart = $customer->cart()->create();
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'sale_price' => $product->sale_price,
        ]);

        $coupon = Coupon::create([
            'code' => 'TEST30',
            'discount_percent' => 30,
            'is_active' => true,
        ]);

        $this->actingAs($customer);

        Livewire::test(Checkout::class)
            ->assertSet('couponDiscountPercent', 0)
            ->set('couponCode', 'TEST30')
            ->call('applyCoupon')
            ->assertSet('couponDiscountPercent', 30)
            ->assertSet('appliedCouponId', $coupon->id)
            ->assertSet('total', 700) // 1000 - 30% discount
            ->call('removeCoupon')
            ->assertSet('couponDiscountPercent', 0)
            ->assertSet('appliedCouponId', null)
            ->assertSet('total', 1000);
    }

    public function test_product_comparison_session_state(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $category = Category::create(['name' => 'Networking']);
        $brand = Brand::create(['name' => 'Netgear']);
        $product = Product::create([
            'name' => 'Router X',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 500,
        ]);

        $this->actingAs($customer);

        Livewire::test(Compare::class)
            ->call('toggleComparison', $product->id);

        $this->assertEquals([$product->id], session()->get('compared_product_ids'));

        Livewire::test(Compare::class)
            ->call('toggleComparison', $product->id);

        $this->assertEquals([], session()->get('compared_product_ids'));
    }

    public function test_contact_messages_read_unread_indicator(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $message = ContactMessage::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'subject' => 'Support',
            'message' => 'Please help me.',
        ]);

        $this->assertFalse($message->fresh()->is_read);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\ContactMessages\Index::class)
            ->call('viewMessage', $message->id)
            ->assertSet('showMessageModal', true);

        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_super_admin_notified_on_successful_verify_payment(): void
    {
        Notification::fake();

        $mock = \Mockery::mock(\Razorpay\Api\Utility::class);
        $mock->shouldReceive('verifyPaymentSignature')->once()->andReturn(null);
        $this->app->instance(\Razorpay\Api\Utility::class, $mock);

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $category = Category::create(['name' => 'Networking']);
        $brand = Brand::create(['name' => 'Netgear']);
        $product = Product::create([
            'name' => 'Paid Router',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
            'sale_price' => 500,
            'stock' => 10,
        ]);

        $address = $customer->addresses()->create([
            'full_name' => 'John',
            'phone' => '1234567890',
            'address_line1' => '123 Street',
            'city' => 'City',
            'state' => 'State',
            'pincode' => '123456',
        ]);

        $order = \App\Models\Order::create([
            'user_id' => $customer->id,
            'address_id' => $address->id,
            'shipping_name' => $address->full_name,
            'shipping_phone' => $address->phone,
            'shipping_address_line1' => $address->address_line1,
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_pincode' => $address->pincode,
            'shipping_country' => 'India',
            'subtotal' => 500,
            'discount' => 0,
            'shipping_fee' => 0,
            'tax_amount' => 0,
            'total' => 500,
            'payment_method' => 'razorpay',
            'payment_status' => 'pending',
            'status' => 'pending',
            'razorpay_order_id' => 'r_123',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 500,
            'quantity' => 1,
        ]);

        // Add to cart so Checkout can mount successfully
        $cart = $customer->cart()->create();
        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'sale_price' => $product->sale_price,
        ]);

        $this->actingAs($customer);

        Livewire::test(Checkout::class)
            ->call('verifyPayment', 'pay_123', 'r_123', 'sig_123')
            ->assertRedirect(route('shop.order.confirmation', $order->order_number));

        Notification::assertSentTo(
            $admin,
            AdminNewOrderNotification::class
        );
    }
}
