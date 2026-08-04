<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('order_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('invoice_number')->unique();
            $table->string('financial_year', 4); // e.g. '2627' for FY 2026-27
            $table->unsignedInteger('sequence'); // per-financial-year running number

            $table->date('invoice_date');
            $table->string('place_of_supply');
            $table->boolean('is_inter_state')->default(false);

            $table->decimal('taxable_amount', 10, 2)->default(0);
            $table->decimal('cgst_amount', 10, 2)->default(0);
            $table->decimal('sgst_amount', 10, 2)->default(0);
            $table->decimal('igst_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->timestamps();

            $table->unique(['financial_year', 'sequence']);
            $table->index('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
