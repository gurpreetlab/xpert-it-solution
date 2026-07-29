<?php

namespace App\Livewire\Admin\Icecat;

use App\Jobs\ProcessIcecatImportBatch;
use App\Models\Category;
use App\Models\IcecatImportBatch;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Import extends Component
{
    /**
     * Each row is an independent category + line-list pair, so one
     * submission can queue several categories at once instead of the
     * CLI's one-category-per-run limitation.
     */
    public array $rows = [];
 
    public function mount(): void
    {
        $this->rows = [$this->blankRow()];
    }
 
    protected function blankRow(): array
    {
        return ['key' => (string) Str::uuid(), 'category_id' => '', 'input' => ''];
    }
 
    public function addRow(): void
    {
        $this->rows[] = $this->blankRow();
    }
 
    public function removeRow(string $key): void
    {
        if (count($this->rows) <= 1) {
            return;
        }
 
        $this->rows = array_values(array_filter($this->rows, fn ($row) => $row['key'] !== $key));
    }
 
    public function submit(): void
    {
        $this->validate([
            'rows' => 'required|array|min:1',
            'rows.*.category_id' => 'required|exists:categories,id',
            'rows.*.input' => 'required|string',
        ], [
            'rows.*.category_id.required' => 'Select a category for every row.',
            'rows.*.input.required' => 'Paste at least one GTIN or ProductCode per row.',
        ]);
 
        $queued = 0;
 
        foreach ($this->rows as $row) {
            $lines = array_values(array_filter(array_map('trim', explode("\n", $row['input']))));
 
            if (empty($lines)) {
                continue;
            }
 
            $batch = IcecatImportBatch::create([
                'category_id' => $row['category_id'],
                'created_by' => Auth::id(),
                'raw_input' => implode("\n", $lines),
                'total' => count($lines),
                'status' => 'pending',
            ]);
 
            ProcessIcecatImportBatch::dispatch($batch->id);
            $queued++;
        }
 
        $this->rows = [$this->blankRow()];
 
        Flux::toast(variant: 'success', text: "{$queued} import batch(es) queued. Track progress below.");
    }
 
    #[Computed]
    public function categories()
    {
        return Category::orderBy('name')->get(['id', 'name']);
    }
 
    #[Computed]
    public function recentBatches()
    {
        return IcecatImportBatch::with(['category:id,name', 'createdBy:id,name'])
            ->latest()
            ->limit(20)
            ->get();
    }
 
    #[Computed]
    public function hasActiveBatches(): bool
    {
        return $this->recentBatches->contains(fn ($batch) => ! $batch->isFinished());
    }
    
    public function render()
    {
        return view('livewire.admin.icecat.import');
    }
}
