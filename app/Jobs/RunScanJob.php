<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use App\Models\Scan;
use App\Services\ScannerService;
use App\Services\RiskScorer;

class RunScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $scanId; // Accept ID, not the model

    /**
     * Create a new job instance.
     */
    public function __construct(int $scanId)
    {
        $this->scanId = $scanId;
    }

    /**
     * Optional middleware (limit retries per minute).
     */
    public function middleware()
    {
        return [new ThrottlesExceptions(5, 2)];
    }

    /**
     * Execute the job.
     */
    public function handle(ScannerService $scanner, RiskScorer $scorer)
    {
        $scan = Scan::find($this->scanId);

        if (!$scan) {
            \Log::warning("RunScanJob: Scan record not found for ID {$this->scanId}");
            return;
        }

        // Mark scan as running
        $scan->update([
            'status'     => 'running',
            'started_at' => now(),
        ]);

        try {
            // Map scan_depth to numeric depth
            $depthMap = [
                'quick'    => 1,
                'standard' => 2,
                'deep'     => 3,
            ];

            $depthKey = $scan->scan_depth ?? 'quick';
            $depth    = $depthMap[$depthKey] ?? 1;

            // Run the scan
            $findings = $scanner->run($scan->target_url, $depth, $scan);

            // Compute risk score
            $score = $scorer->score($findings);

            // Update scan record
            $scan->update([
                'status'       => 'completed',
                'findings'     => $findings,
                'risk_score'   => $score,
                'completed_at' => now(),
            ]);

            // Persist individual findings if relation exists
            if (method_exists($scan, 'findings')) {
                foreach ($findings as $f) {
                    $scan->findings()->create([
                        'category'    => $f['category'] ?? 'general',
                        'title'       => $f['title'] ?? 'Untitled',
                        'description' => $f['description'] ?? null,
                        'severity'    => $f['severity'] ?? 'medium',
                        'score'       => $f['score'] ?? null,
                        'evidence'    => $f['evidence'] ?? null,
                    ]);
                }
            }

        } catch (\Throwable $e) {
            \Log::error("❌ RunScanJob failed for scan {$scan->id}: " . $e->getMessage());
            $scan->update(['status' => 'failed']);
        }
    }
}
