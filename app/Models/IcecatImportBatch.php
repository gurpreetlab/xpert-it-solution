<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category_id', 'created_by', 'raw_input', 'status', 'total', 'imported', 'skipped', 'failures', 'error', 'started_at', 'finished_at'])]
class IcecatImportBatch extends Model
{
    protected $casts = [
        'failures' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getProgressPercentAttribute(): int
    {
        if ($this->total === 0) {
            return 0;
        }

        return (int) round((($this->imported + $this->skipped) / $this->total) * 100);
    }

    public function isFinished(): bool
    {
        return in_array($this->status, ['completed', 'failed'], true);
    }
}
