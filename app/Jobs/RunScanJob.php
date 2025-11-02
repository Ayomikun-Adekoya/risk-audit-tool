<?php 

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Support\Facades\Log;
use App\Models\Scan;
use App\Services\ScannerService;

class RunScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $scanId;

    public function __construct(int $scanId)
    {
        $this->scanId = $scanId;
    }

    public function middleware()
    {
        return [new ThrottlesExceptions(5, 2)];
    }

    public function handle(ScannerService $scanner): void
    {
        $scan = Scan::find($this->scanId);
        if (!$scan) {
            Log::warning("⚠️ Scan record not found for ID {$this->scanId}");
            return;
        }

        Log::info("🚀 RunScanJob started for scan #{$scan->id} ({$scan->target_url})");

        $scan->update(['status'=>'running','started_at'=>now()]);

        try {
            $result = $scanner->run($scan);

            $scan->status       = 'completed';
            $scan->results      = $result; // array saved correctly
            $scan->risk_score   = $result['summary']['score'] ?? null;
            $scan->completed_at = now();
            $scan->save();

            $summary = $result['summary'] ?? [];
            Log::info("✅ Scan #{$scan->id} completed", [
                'passed' => $summary['passed'] ?? 0,
                'failed' => $summary['failed'] ?? 0,
                'score'  => $summary['score'] ?? 'N/A',
            ]);

        } catch (\Throwable $e) {
            Log::error("❌ RunScanJob failed for scan #{$scan->id}: {$e->getMessage()}", [
                'trace'=>$e->getTraceAsString()
            ]);

            $scan->update([
                'status'=>'failed',
                'error_message'=>$e->getMessage(),
                'completed_at'=>now(),
            ]);

            throw $e;
        }
    }
}
