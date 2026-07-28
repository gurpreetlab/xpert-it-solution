<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("order_items", function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained()->cascadeOnDelete();

            $table
                ->foreignId("product_id")
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string("product_name");
            $table->string("sku")->nullable();
            $table->string("hsn_code")->nullable();
            $table->decimal("unit_price", 10, 2);
            $table->decimal("mrp", 10, 2)->default(0);

            // CGST
            $table->decimal("cgst_rate", 5, 2)->default(0);
            $table->decimal("cgst_amount", 10, 2)->default(0);

            // SGST
            $table->decimal("sgst_rate", 5, 2)->default(0);
            $table->decimal("sgst_amount", 10, 2)->default(0);

            // GST
            $table->decimal("gst_rate", 5, 2)->default(0);
            $table->decimal("gst_amount", 10, 2)->default(0);

            $table->unsignedInteger("quantity");
            $table->timestamps();

            $table->index("order_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("order_items");
    }
};
