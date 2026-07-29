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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('group_name')->nullable(); // e.g. "General", "Connectivity", "Power"
            $table->string('key');
            $table->text('value');
            $table->string('unit')->nullable(); // e.g. "Mbps", "GB", "dBi" 

            $table->unsignedInteger('icecat_feature_id')->nullable();

            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('icecat_feature_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
