<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.auth.split')]
#[Title('Login with Phone Number')]
class PhoneLogin extends Component
{
    public string $phone = '';
    public string $otp = '';
    public int $step = 1;
    public ?string $generatedOtp = null;

    public function sendOtp(): void
    {
        $this->validate([
            'phone' => ['required', 'regex:/^[0-9]{10,12}$/'],
        ], [
            'phone.regex' => 'Please enter a valid 10 to 12 digit phone number.',
        ]);

        // Generate a 6-digit OTP
        $otpCode = (string) rand(100000, 999999);
        $this->generatedOtp = $otpCode;

        // Store OTP in database or session
        $user = User::where('phone', $this->phone)->first();
        if ($user) {
            $user->update([
                'phone_otp' => $otpCode,
                'phone_otp_expires_at' => now()->addMinutes(10),
            ]);
        } else {
            session([
                'phone_otp_' . $this->phone => [
                    'code' => $otpCode,
                    'expires_at' => now()->addMinutes(10),
                ]
            ]);
        }

        $this->step = 2;
        session()->flash('message', "OTP sent successfully! (For testing, your OTP is: {$otpCode})");
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = User::where('phone', $this->phone)->first();

        if ($user) {
            if ($user->phone_otp !== $this->otp || ($user->phone_otp_expires_at && now()->gt($user->phone_otp_expires_at))) {
                $this->addError('otp', 'Invalid or expired OTP. Please try again.');
                return;
            }

            // Clear OTP
            $user->update([
                'phone_otp' => null,
                'phone_otp_expires_at' => null,
            ]);
        } else {
            $storedSession = session('phone_otp_' . $this->phone);
            if (!$storedSession || $storedSession['code'] !== $this->otp || now()->gt($storedSession['expires_at'])) {
                $this->addError('otp', 'Invalid or expired OTP. Please try again.');
                return;
            }

            session()->forget('phone_otp_' . $this->phone);

            // Create new user with phone
            $user = User::create([
                'name' => 'User ' . substr($this->phone, -4),
                'email' => $this->phone . '@phone.xpertit.loc',
                'phone' => $this->phone,
                'password' => Hash::make(Str::random(24)),
                'email_verified_at' => now(),
            ]);

            Role::firstOrCreate(['name' => 'customer']);
            $user->assignRole('customer');
        }

        Auth::login($user, true);

        $targetRoute = $user->hasRole('super-admin') ? route('dashboard') : route('home');

        return redirect()->intended($targetRoute);
    }

    public function render()
    {
        return view('livewire.auth.phone-login');
    }
}
