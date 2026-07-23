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
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table->string("order_number")->unique();

            $table->foreignId("user_id")->constrained()->cascadeOnDelete();

            $table
                ->foreignId("address_id")
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string("shipping_name");
            $table->string("shipping_phone");
            $table->string("shipping_address_line1");
            $table->string("shipping_address_line2")->nullable();
            $table->string("shipping_city");
            $table->string("shipping_state");
            $table->string("shipping_pincode");
            $table->string("shipping_country")->default("India");

            $table->decimal("subtotal", 10, 2)->default(0);
            $table->decimal("discount", 10, 2)->default(0);
            $table->decimal("shipping_fee", 10, 2)->default(0);
            $table->decimal("tax_amount", 10, 2)->default(0);
            $table->decimal("total", 10, 2)->default(0);

            $table->string("payment_method")->default("razorpay");
            $table->string("payment_status")->default("pending"); // "pending", "paid", "failed", "refunded"
            $table->string("razorpay_order_id")->nullable();
            $table->string("razorpay_payment_id")->nullable();
            $table->string("razorpay_signature")->nullable();

            $table->string("status")->default("pending"); // "pending", "processing", "shipped", "delivered", "cancelled",
            $table->timestamps();

            $table->index("order_number");
            $table->index("user_id");
            $table->index("razorpay_order_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("orders");
    }
};
