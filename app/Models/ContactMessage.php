<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'email', 'phone', 'subject', 'message', 'user_id', 'read_at'])]
class ContactMessage extends Model
{
    #[\Override]
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjectLabel(): string
    {
        return match ($this->subject) {
            'sales' => 'Sales Enquiry',
            'bulk_order' => 'Bulk / Corporate Order',
            'support' => 'Product Support',
            'other' => 'Something Else',
            default => 'General Enquiry',
        };
    }

    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }
}
