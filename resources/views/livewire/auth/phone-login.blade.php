<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Login with Phone')" :description="__('Enter your phone number to receive a one-time verification code')" />

    @if (session()->has('message'))
        <div class="p-3 text-xs font-semibold rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
            {{ session('message') }}
        </div>
    @endif

    @if ($step === 1)
        <form wire:submit="sendOtp" class="flex flex-col gap-6">
            <div>
                <label for="phone" class="block text-xs font-semibold text-zinc-700 mb-1">Phone Number</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-sm font-semibold text-zinc-500">+91</span>
                    <input
                        type="text"
                        wire:model="phone"
                        id="phone"
                        placeholder="9876543210"
                        required
                        autofocus
                        class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-zinc-200 bg-white text-sm font-medium text-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>
                @error('phone') <span class="text-xs text-rose-600 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <flux:button type="submit" variant="primary" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium cursor-pointer">
                Send OTP
            </flux:button>
        </form>
    @else
        <form wire:submit="verifyOtp" class="flex flex-col gap-6">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="otp" class="block text-xs font-semibold text-zinc-700">Enter 6-Digit Verification Code</label>
                    <button type="button" wire:click="$set('step', 1)" class="text-xs text-blue-600 font-semibold hover:underline">Change Number</button>
                </div>
                <input
                    type="text"
                    wire:model="otp"
                    id="otp"
                    placeholder="123456"
                    maxlength="6"
                    required
                    autofocus
                    class="w-full px-4 py-2.5 text-center tracking-widest text-lg font-bold rounded-xl border border-zinc-200 bg-white text-zinc-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                @error('otp') <span class="text-xs text-rose-600 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <flux:button type="submit" variant="primary" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium cursor-pointer">
                Verify & Log In
            </flux:button>
        </form>
    @endif

    <div class="space-x-1 text-sm text-center text-zinc-600">
        <span>Or return to</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Standard Login') }}</flux:link>
    </div>
</div>
