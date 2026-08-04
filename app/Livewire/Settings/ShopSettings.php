<?php

namespace App\Livewire\Settings;

use App\Models\ShopSetting;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Title('Shop settings')]
#[Layout('layouts.blank')]
class ShopSettings extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $gstin = '';

    public string $address_line1 = '';

    public ?string $address_line2 = '';

    public string $state = '';

    public string $state_code = '';

    public string $phone = '';

    public string $email = '';

    public ?string $bank_account_number = '';

    public ?string $bank_ifsc = '';

    public float $cgst_rate = 9;

    public float $sgst_rate = 9;

    public float $gst_rate = 18;

    public ?TemporaryUploadedFile $logo = null;

    public ?TemporaryUploadedFile $signature = null;

    public ?string $existingLogoUrl = null;

    public ?string $existingSignatureUrl = null;

    public function mount(): void
    {
        $settings = ShopSetting::current();

        $this->fill($settings->only([
            'name', 'gstin', 'address_line1', 'address_line2', 'state', 'state_code',
            'phone', 'email', 'bank_account_number', 'bank_ifsc',
            'cgst_rate', 'sgst_rate', 'gst_rate',
        ]));

        $this->existingLogoUrl = $settings->logoUrl();
        $this->existingSignatureUrl = $settings->signatureUrl();
    }

    /** @return array<string, mixed> */
    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'gstin' => ['required', 'string', 'max:15'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:100'],
            'state_code' => ['required', 'string', 'max:2'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'bank_ifsc' => ['nullable', 'string', 'max:20'],
            'cgst_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'sgst_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'gst_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'signature' => ['nullable', 'image', 'max:1024'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $settings = ShopSetting::current();

        unset($validated['logo'], $validated['signature']);

        if ($this->logo) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $this->logo->store('shop', 'public');
        }

        if ($this->signature) {
            if ($settings->signature_path) {
                Storage::disk('public')->delete($settings->signature_path);
            }
            $validated['signature_path'] = $this->signature->store('shop', 'public');
        }

        $settings->update($validated);

        $fresh = $settings->fresh();
        $this->existingLogoUrl = $fresh->logoUrl();
        $this->existingSignatureUrl = $fresh->signatureUrl();
        $this->reset(['logo', 'signature']);

        Flux::toast(variant: 'success', text: __('Shop settings updated.'));
    }

    public function render(): View
    {
        return view('livewire.settings.shop-settings');
    }
}
