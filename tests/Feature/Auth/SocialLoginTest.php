<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_url(): void
    {
        $response = $this->get(route('auth.google.redirect'));
        $response->assertRedirect();
    }

    public function test_google_callback_creates_user_and_assigns_customer_role(): void
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-12345');
        $abstractUser->shouldReceive('getName')->andReturn('Google User');
        $abstractUser->shouldReceive('getNickname')->andReturn(null);
        $abstractUser->shouldReceive('getEmail')->andReturn('googleuser@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::where('email', 'googleuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('google-id-12345', $user->google_id);
        $this->assertTrue($user->hasRole('customer'));
    }

    public function test_facebook_redirect_url(): void
    {
        $response = $this->get(route('auth.facebook.redirect'));
        $response->assertRedirect();
    }

    public function test_facebook_callback_creates_user_and_assigns_customer_role(): void
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('facebook-id-67890');
        $abstractUser->shouldReceive('getName')->andReturn('Facebook User');
        $abstractUser->shouldReceive('getNickname')->andReturn(null);
        $abstractUser->shouldReceive('getEmail')->andReturn('fbuser@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://graph.facebook.com/avatar');

        $provider = Mockery::mock('Laravel\Socialite\Two\FacebookProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('facebook')->andReturn($provider);

        $response = $this->get(route('auth.facebook.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::where('email', 'fbuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('facebook-id-67890', $user->facebook_id);
        $this->assertTrue($user->hasRole('customer'));
    }
}
