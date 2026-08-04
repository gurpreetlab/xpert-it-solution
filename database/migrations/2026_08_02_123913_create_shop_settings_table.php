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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gstin');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('state');
            $table->string('state_code', 2);
            $table->string('phone');
            $table->string('email');
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->decimal('cgst_rate', 5, 2)->default(9);
            $table->decimal('sgst_rate', 5, 2)->default(9);
            $table->decimal('gst_rate', 5, 2)->default(18);
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
