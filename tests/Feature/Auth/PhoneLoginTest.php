<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\PhoneLogin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PhoneLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login.phone'));

        $response->assertOk();
    }

    public function test_user_can_request_and_verify_otp(): void
    {
        $component = Livewire::test(PhoneLogin::class)
            ->set('phone', '9876543210')
            ->call('sendOtp');

        $component->assertSet('step', 2);
        $otp = $component->get('generatedOtp');

        $this->assertNotEmpty($otp);

        $component->set('otp', $otp)
            ->call('verifyOtp')
            ->assertRedirect(route('home'));

        $this->assertAuthenticated();

        $user = User::where('phone', '9876543210')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('customer'));
    }

    public function test_existing_user_with_phone_can_login(): void
    {
        $user = User::factory()->create([
            'phone' => '9998887776',
        ]);

        $component = Livewire::test(PhoneLogin::class)
            ->set('phone', '9998887776')
            ->call('sendOtp');

        $user->refresh();
        $this->assertNotNull($user->phone_otp);

        $component->set('otp', $user->phone_otp)
            ->call('verifyOtp')
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }
}
