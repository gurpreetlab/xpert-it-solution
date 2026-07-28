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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
 
            $table->string('vendor')->nullable();       // Microsoft, Kaspersky, Norton, etc.
            $table->string('license_type')->nullable(); // OEM, Retail, ESD, Subscription
 
            // Store this encrypted at the model level (Laravel's `encrypted` cast) -
            // it's a secret, not display data.
            $table->text('license_key');
 
            $table->unsignedSmallInteger('validity_days')->nullable(); // null = lifetime/unlimited
            $table->enum('status', ['available', 'reserved', 'sold', 'revoked'])->default('available');
 
            // Not constrained yet since there's no orders table in this migration set -
            // add ->constrained()->nullOnDelete() once one exists.
            $table->foreignId('order_id')->nullable()->index();
 
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
