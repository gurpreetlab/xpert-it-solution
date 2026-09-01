<?php

namespace App\Livewire\Shop;

use App\Models\ContactMessage;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BulkOrders extends Component
{
    #[Validate('required|string|max:255')]
    public string $company_name = '';

    #[Validate('nullable|string|max:15')]
    public string $gstin = '';

    #[Validate('required|string|max:255')]
    public string $contact_person = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    #[Validate('required|string|max:255')]
    public string $product_requirement = '';

    #[Validate('required|integer|min:1')]
    public int $quantity = 10;

    #[Validate('nullable|string|max:1000')]
    public string $additional_notes = '';

    public function submitQuote(): void
    {
        $this->validate();

        $messageContent = "BUSINESS / BULK QUOTE REQUEST\n" .
            "Company: {$this->company_name}\n" .
            "GSTIN: " . ($this->gstin ?: 'N/A') . "\n" .
            "Contact: {$this->contact_person} ({$this->phone}, {$this->email})\n" .
            "Product Required: {$this->product_requirement}\n" .
            "Quantity Needed: {$this->quantity}\n" .
            "Notes: " . ($this->additional_notes ?: 'None');

        ContactMessage::create([
            'name' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => "Bulk Quote Request: {$this->company_name} ({$this->quantity} units)",
            'message' => $messageContent,
        ]);

        $this->reset(['company_name', 'gstin', 'contact_person', 'email', 'phone', 'product_requirement', 'additional_notes']);
        $this->quantity = 10;

        Flux::toast(
            text: 'Your bulk quote request has been submitted. Our enterprise manager will contact you within 2 business hours.',
            variant: 'success'
        );
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.bulk-orders');
    }
}
