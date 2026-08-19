<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to authenticate with Google.');
        }

        $googleEmail = $googleUser->getEmail();
        $user = User::where('google_id', $googleUser->getId())
            ->when($googleEmail, fn ($query) => $query->orWhere('email', $googleEmail))
            ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
            ]);

            Role::firstOrCreate(['name' => 'customer']);
            $user->assignRole('customer');
        }

        Auth::login($user, true);

        $isSuperAdmin = $user->hasRole('super-admin');
        $intended = session()->get('url.intended');
        if (! $isSuperAdmin && $intended && str_contains($intended, '/super-admin')) {
            session()->forget('url.intended');
        }

        $targetRoute = $isSuperAdmin ? route('dashboard') : route('home');

        return redirect()->intended($targetRoute);
    }

}
