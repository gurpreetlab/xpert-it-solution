<?php

namespace App\Jobs;

use App\Models\IcecatImportBatch;
use App\Services\IcecatProductImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessIcecatImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Icecat's API is rate-limited and each lookup is a real HTTP round
     * trip, so a batch of a few hundred lines can take a while — this
     * must not be constrained by a typical queue worker timeout.
     */
    public int $timeout = 3600;

    public int $tries = 1; // a partial re-run would re-process already-imported lines; retry manually via the UI instead

    public function __construct(public int $batchId)
    {
    }

    public function handle(IcecatProductImporter $importer): void
    {
        $batch = IcecatImportBatch::find($this->batchId);

        if (! $batch) {
            return;
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $batch->raw_input))));

        $batch->update([
            'status' => 'processing',
            'total' => count($lines),
            'started_at' => now(),
        ]);

        try {
            $result = $importer->importLines($lines, $batch->category, function ($imported, $skipped, $failures) use ($batch) {
                // Persist progress after every single line so the admin UI's
                // polling reflects real-time status, not just the end result.
                $batch->update([
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'failures' => $failures,
                ]);
            });

            $batch->update([
                'status' => 'completed',
                'imported' => $result['imported'],
                'skipped' => $result['skipped'],
                'failures' => $result['failures'],
                'finished_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            $batch->update([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'finished_at' => now(),
            ]);
        }
    }
}