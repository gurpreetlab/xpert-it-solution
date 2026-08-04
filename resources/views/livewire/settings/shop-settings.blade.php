<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Shop settings') }}</flux:heading>

    <x-settings.layout :heading="__('Shop Settings')" :subheading="__('Update company, GST, and branding details shown across the site and invoices')">
        <form wire:submit="save" class="my-6 w-full space-y-6">

            <div class="flex items-center gap-6">
                <div>
                    <flux:text class="mb-2! font-medium text-black dark:text-white">{{ __('Logo') }}</flux:text>
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="size-16 rounded-lg object-contain border border-zinc-200 dark:border-zinc-800" />
                    @elseif ($existingLogoUrl)
                        <img src="{{ $existingLogoUrl }}" class="size-16 rounded-lg object-contain border border-zinc-200 dark:border-zinc-800" />
                    @endif
                    <input type="file" wire:model="logo" accept="image/*" class="mt-2! text-xs" />
                    @error('logo') <flux:text class="text-red-500 text-xs mt-1!">{{ $message }}</flux:text> @enderror
                </div>

                <div>
                    <flux:text class="mb-2! font-medium text-black dark:text-white">{{ __('Signature (for invoices)') }}</flux:text>
                    @if ($signature)
                        <img src="{{ $signature->temporaryUrl() }}" class="h-16 object-contain border border-zinc-200 dark:border-zinc-800" />
                    @elseif ($existingSignatureUrl)
                        <img src="{{ $existingSignatureUrl }}" class="h-16 object-contain border border-zinc-200 dark:border-zinc-800" />
                    @endif
                    <input type="file" wire:model="signature" accept="image/*" class="mt-2! text-xs" />
                    @error('signature') <flux:text class="text-red-500 text-xs mt-1!">{{ $message }}</flux:text> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12">
                <flux:input wire:model="name" :label="__('Company name')" required />
                <flux:input wire:model="gstin" :label="__('GSTIN')" required />
                <flux:input wire:model="address_line1" :label="__('Address line 1')" required />
                <flux:input wire:model="address_line2" :label="__('Address line 2')" />
                <flux:input wire:model="state" :label="__('State')" required />
                <flux:input wire:model="state_code" :label="__('State code')" required />
                <flux:input wire:model="phone" :label="__('Phone')" required />
                <flux:input wire:model="email" type="email" :label="__('Email')" required />
                <flux:input wire:model="bank_account_number" :label="__('Bank account number')" />
                <flux:input wire:model="bank_ifsc" :label="__('Bank IFSC')" />
            </div>

            <flux:heading size="sm" class="mt-12">{{ __('GST Rates') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <flux:input wire:model="cgst_rate" type="number" step="0.01" :label="__('CGST %')" required />
                <flux:input wire:model="sgst_rate" type="number" step="0.01" :label="__('SGST %')" required />
                <flux:input wire:model="gst_rate" type="number" step="0.01" :label="__('IGST / Total GST %')" required />
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</main>