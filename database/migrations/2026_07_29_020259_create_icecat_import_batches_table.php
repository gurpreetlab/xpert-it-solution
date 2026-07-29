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
        Schema::create('icecat_import_batches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Newline-separated "GTIN" or "ProductCode,Brand" entries, kept
            // on the row itself so the queued job doesn't depend on a
            // serialized payload and progress survives a queue restart.
            $table->longText('raw_input');

            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');

            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('imported')->default(0);
            $table->unsignedInteger('skipped')->default(0);

            // JSON list of {term, reason} for every skipped/failed line,
            // so an admin can see *why* without digging through logs.
            $table->json('failures')->nullable();

            $table->text('error')->nullable(); // set only if the whole batch aborts
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icecat_import_batches');
    }
};
