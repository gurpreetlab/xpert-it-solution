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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('type', ['physical', 'license'])->default('physical');

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('sku')->nullable()->unique();
            $table->string('mpn')->nullable(); // manufacturer part number
            $table->string('gtin')->nullable(); // EAN / UPC barcode - Icecat's other lookup key
            $table->string('hsn_code')->nullable();

            $table->unsignedInteger('icecat_id')->nullable()->unique();
            $table->timestamp('icecat_synced_at')->nullable();

            $table->decimal('mrp', 10, 2)->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);

            $table->unsignedInteger('stock')->default(0);

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->decimal('weight', 8, 3)->nullable(); // kg, for shipping calculations
            $table->string('warranty')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('sku');
            $table->index('mpn');
            $table->index('gtin');
            $table->index('hsn_code');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
