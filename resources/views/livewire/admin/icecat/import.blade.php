@php
    $statusColors = [
        'pending' => 'zinc',
        'processing' => 'blue',
        'completed' => 'emerald',
        'failed' => 'red',
    ];
@endphp

<div class="space-y-6">

    <div>
        <flux:heading size="xl" level="1">Icecat Product Import</flux:heading>
        <flux:text class="mt-1 text-gray-500">Pull product data, images and specifications straight from Icecat — no terminal or text files needed.</flux:text>
    </div>

    <!-- Import Form -->
    <form wire:submit="submit" class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 space-y-5">

        @foreach($rows as $index => $row)
            <div wire:key="row-{{ $row['key'] }}" class="grid grid-cols-1 lg:grid-cols-3 gap-4 pb-5 {{ !$loop->last ? 'border-b border-neutral-200 dark:border-neutral-700' : '' }}">
                <div>
                    <flux:select wire:model="rows.{{ $index }}.category_id" label="Category" placeholder="Select category">
                        @foreach($this->categories as $category)
                            <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error("rows.{$index}.category_id")
                        <flux:text class="text-red-500 text-xs mt-1">{{ $message }}</flux:text>
                    @enderror

                    @if(count($rows) > 1)
                        <flux:button wire:click="removeRow('{{ $row['key'] }}')" variant="ghost" size="sm" icon="trash" class="mt-2 text-red-500">
                            Remove this category
                        </flux:button>
                    @endif
                </div>

                <div class="lg:col-span-2">
                    <flux:textarea
                        wire:model="rows.{{ $index }}.input"
                        label="GTINs / Product Codes"
                        placeholder="One per line — either a bare GTIN (EAN/UPC), or ProductCode,Brand&#10;e.g.&#10;8886419388996&#10;C7Z70A,HP"
                        rows="5"
                    />
                    @error("rows.{$index}.input")
                        <flux:text class="text-red-500 text-xs mt-1">{{ $message }}</flux:text>
                    @enderror
                    <flux:text class="text-xs text-gray-500 mt-1">
                        A brand is required whenever you search by product code — a code is only unique within a brand. GTIN alone is fine.
                    </flux:text>
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-between">
            <flux:button type="button" wire:click="addRow" variant="ghost" size="sm" icon="plus">
                Add Another Category
            </flux:button>

            <flux:button type="submit" variant="primary" icon="arrow-up-tray">
                Queue Import
            </flux:button>
        </div>
    </form>

    <!-- Import History -->
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5" @if($this->hasActiveBatches) wire:poll.5000ms @endif>
        <flux:heading size="sm" class="mb-4">Import History</flux:heading>

        <div class="space-y-4">
            @forelse($this->recentBatches as $batch)
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">{{ $batch->category->name }}</span>
                            <flux:badge size="sm" color="{{ $statusColors[$batch->status] ?? 'zinc' }}" class="capitalize">{{ $batch->status }}</flux:badge>
                        </div>
                        <span class="text-xs text-gray-500">
                            Queued by {{ $batch->createdBy?->name ?? 'Unknown' }} &middot; {{ $batch->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if($batch->status === 'processing' || $batch->status === 'completed')
                        <div class="h-1.5 rounded-full bg-neutral-100 dark:bg-neutral-800 overflow-hidden mb-2">
                            <div class="h-full rounded-full {{ $batch->status === 'completed' ? 'bg-emerald-500' : 'bg-blue-500' }}" style="width: {{ $batch->progress_percent }}%"></div>
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500">
                        <span>Total: <strong class="text-current">{{ $batch->total }}</strong></span>
                        <span class="text-emerald-500">Imported: <strong>{{ $batch->imported }}</strong></span>
                        <span class="text-amber-500">Skipped: <strong>{{ $batch->skipped }}</strong></span>
                        @if($batch->started_at)
                            <span>Started {{ $batch->started_at->diffForHumans() }}</span>
                        @endif
                        @if($batch->finished_at)
                            <span>Finished {{ $batch->finished_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    @if($batch->status === 'failed' && $batch->error)
                        <div class="mt-2 text-xs text-red-500">{{ $batch->error }}</div>
                    @endif

                    @if(!empty($batch->failures))
                        <flux:modal.trigger name="failures-{{ $batch->id }}">
                            <flux:button variant="ghost" size="sm" class="mt-2 cursor-pointer">
                                View {{ count($batch->failures) }} skipped item(s)
                            </flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="failures-{{ $batch->id }}" class="min-w-[28rem]">
                            <div class="space-y-4">
                                <flux:heading size="lg">Skipped Items — {{ $batch->category->name }}</flux:heading>
                                <div class="max-h-80 overflow-y-auto space-y-2">
                                    @foreach($batch->failures as $failure)
                                        <div class="text-sm border-b border-neutral-100 dark:border-neutral-800 pb-2">
                                            <span class="font-mono font-semibold">{{ $failure['term'] }}</span>
                                            <div class="text-xs text-gray-500">{{ $failure['reason'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </flux:modal>
                    @endif
                </div>
            @empty
                <flux:text class="text-gray-500 text-sm">No imports have been run yet.</flux:text>
            @endforelse
        </div>
    </div>
</div>