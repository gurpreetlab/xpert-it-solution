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

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('sku')->nullable()->unique();
            $table->string('hsn_code')->nullable();

            $table->decimal('mrp', 10, 2)->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);

            $table->unsignedInteger('stock')->default(0);

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('slug');
            $table->index('sku');
            $table->index('hsn_code');
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
