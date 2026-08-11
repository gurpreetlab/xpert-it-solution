<?php

namespace App\Livewire\Admin\ContactMessages;

use App\Models\ContactMessage;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $selectedMessageId = null;

    public bool $showMessageModal = false;

    /**
     * Get paginated contact messages.
     */
    #[Computed]
    public function messages()
    {
        return ContactMessage::latest()->paginate(10);
    }

    /**
     * Show detail of a message.
     */
    public function viewMessage(int $id): void
    {
        $this->selectedMessageId = $id;
        $this->showMessageModal = true;
    }

    /**
     * Get the currently selected message.
     */
    #[Computed]
    public function selectedMessage(): ?ContactMessage
    {
        return $this->selectedMessageId
            ? ContactMessage::find($this->selectedMessageId)
            : null;
    }

    /**
     * Delete a contact message.
     */
    public function deleteMessage(int $id): void
    {
        $message = ContactMessage::find($id);

        if ($message) {
            $message->delete();
            Flux::toast(text: 'Message deleted successfully.', variant: 'success');
        }

        if ($this->selectedMessageId === $id) {
            $this->showMessageModal = false;
            $this->selectedMessageId = null;
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.admin.contact-messages.index', [
            'messages' => $this->messages,
        ]);
    }
}
