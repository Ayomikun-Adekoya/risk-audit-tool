<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scan;
use App\Services\ScannerService;
use Illuminate\Support\Facades\Log;

class BackfillScanResults extends Command
{
    protected $signature = 'scans:backfill-results';
    protected $description = 'Backfill results column for existing completed scans';

    public function handle(ScannerService $scanner)
    {
        $scans = Scan::whereNotNull('status')
                     ->where('status', 'completed')
                     ->whereNull('results')
                     ->get();

        if ($scans->isEmpty()) {
            $this->info('No scans to backfill.');
            return 0;
        }

        foreach ($scans as $scan) {
            try {
                $this->info("Backfilling scan #{$scan->id} ({$scan->target_url})...");
                $result = $scanner->run($scan);

                $scan->update([
                    'results' => json_encode($result),
                    'risk_score' => $result['summary']['score'] ?? null,
                ]);

                $this->info("✅ Scan #{$scan->id} updated successfully.");
            } catch (\Throwable $e) {
                $this->error("❌ Failed to backfill scan #{$scan->id}: {$e->getMessage()}");
                Log::error("BackfillScanResults error", ['scan_id' => $scan->id, 'trace' => $e->getTraceAsString()]);
            }
        }

        $this->info('Backfill complete.');
        return 0;
    }
}
