<div class="space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl" level="1">Invoices</flux:heading>
            <flux:text class="mt-1 text-gray-500">GST invoices generated for paid orders</flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <flux:input
            wire:model.live.debounce.400ms="search"
            placeholder="Search invoice #, order # or customer..."
            icon="magnifying-glass"
            class="lg:col-span-2"
        />

        @if ($search !== '')
            <flux:button variant="ghost" icon="x-mark" wire:click="clearFilters" title="Clear filters" />
        @endif
    </div>

    <!-- Invoices Table -->
    <flux:table :paginate="$invoices">
        <flux:table.columns>
            <flux:table.column>Invoice #</flux:table.column>
            <flux:table.column>Order #</flux:table.column>
            <flux:table.column>Customer</flux:table.column>
            <flux:table.column>Invoice Date</flux:table.column>
            <flux:table.column>Place of Supply</flux:table.column>
            <flux:table.column>Taxable Amt</flux:table.column>
            <flux:table.column>GST</flux:table.column>
            <flux:table.column>Total</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse($invoices as $invoice)
                <flux:table.row :key="$invoice->id">
                    <flux:table.cell class="whitespace-nowrap font-mono">{{ $invoice->invoice_number }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <a href="{{ route('dashboard.orders.show', $invoice->order) }}" wire:navigate class="font-mono hover:text-emerald-400">
                            {{ $invoice->order->order_number }}
                        </a>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <div class="flex flex-col">
                            <span>{{ $invoice->order->user?->name ?? 'Deleted user' }}</span>
                            <span class="text-xs text-gray-500">{{ $invoice->order->user?->email }}</span>
                        </div>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">{{ $invoice->invoice_date->format('d-m-Y') }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        {{ $invoice->place_of_supply }}
                        <flux:badge size="sm" color="{{ $invoice->is_inter_state ? 'purple' : 'blue' }}" class="ml-1">
                            {{ $invoice->is_inter_state ? 'IGST' : 'CGST+SGST' }}
                        </flux:badge>
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">₹{{ number_format($invoice->taxable_amount, 2) }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        ₹{{ number_format($invoice->cgst_amount + $invoice->sgst_amount + $invoice->igst_amount, 2) }}
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap font-semibold">₹{{ number_format($invoice->total_amount, 2) }}</flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        <flux:button size="sm" href="{{ route('dashboard.invoices.download', $invoice) }}" icon="arrow-down-tray">
                            Download
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="9" class="text-center">No invoices generated yet.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>
