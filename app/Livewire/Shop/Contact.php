<?php

namespace App\Livewire\Shop;

use App\Models\ContactMessage;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Contact extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $subject = '';

    public string $message = '';

    /**
     * @return array<string, array<int, string>|string>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'in:sales,bulk_order,support,other'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'message.min' => 'Please give us a few more details (at least 10 characters).',
        ];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        ContactMessage::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);

        session()->flash('success', "Thanks for reaching out! We've received your message and will get back to you shortly.");
    }

    #[Layout('layouts.blank')]
    public function render(): View
    {
        return view('livewire.shop.contact');
    }
}
