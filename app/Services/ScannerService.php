<?php

namespace App\Services;

use App\Models\Scan;
use App\Services\OwaspAnalyzer;
use Illuminate\Support\Facades\Log;

class ScannerService
{
    protected array $depthChecks = [
        'quick' => [
            \App\Services\Checks\SecurityHeadersCheck::class,
            \App\Services\Checks\TlsCheck::class,
        ],
        'standard' => [
            \App\Services\Checks\SecurityHeadersCheck::class,
            \App\Services\Checks\TlsCheck::class,
            \App\Services\Checks\RobotsCheck::class,
        ],
        'deep' => [
            \App\Services\Checks\SecurityHeadersCheck::class,
            \App\Services\Checks\TlsCheck::class,
            \App\Services\Checks\RobotsCheck::class,
            \App\Services\Checks\ExposedFilesCheck::class,
            \App\Services\Checks\DirectoryListingCheck::class,
            \App\Services\Checks\SqlInjectionPassiveCheck::class,
        ],
    ];

    public function run(Scan $scan): array
    {
        $url = $scan->target_url;
        $depth = $scan->scan_depth;

        // Ensure scan is marked running
        $scan->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        if (is_numeric($depth)) {
            $depth = match ((int)$depth) {
                1 => 'quick',
                2 => 'standard',
                3 => 'deep',
                default => 'quick',
            };
        }

        $checks = $this->depthChecks[$depth] ?? $this->depthChecks['quick'];
        Log::info("🚀 Starting '{$depth}' scan for {$url} (" . count($checks) . " checks)");

        $results = [];
        $metrics = [
            'uses_https' => str_starts_with($url, 'https://'),
            'sql_injections_detected' => 0,
            'open_ports_count' => 0,
            'access_control_issues' => 0,
            'weak_passwords_detected' => 0,
            'has_logging_enabled' => null,
            'ssrf_detected' => null,
        ];

        foreach ($checks as $checkClass) {
            if (!class_exists($checkClass)) {
                Log::warning("⚠️ Missing check class: {$checkClass}");
                continue;
            }

            $checker = app($checkClass);

            try {
                $findings = $checker->run($url, $depth, $scan);

                if (!empty($findings) && is_array($findings)) {
                    $results = array_merge($results, $findings);

                    foreach ($findings as $finding) {
                        if (is_array($finding)) {
                            foreach ($metrics as $key => $value) {
                                if (isset($finding[$key])) {
                                    $metrics[$key] = $finding[$key];
                                }
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::error("❌ {$checkClass} failed: {$e->getMessage()}");
                $results[] = [
                    'category' => 'Internal Error',
                    'title' => class_basename($checkClass),
                    'description' => "Exception: {$e->getMessage()}",
                    'severity' => 'info',
                ];
            }
        }

        $owaspResults = OwaspAnalyzer::analyze((object) $metrics);
        $passed = collect($owaspResults)->where('status', 'Passed')->count();
        $failed = collect($owaspResults)->where('status', 'Failed')->count();
        $score = count($owaspResults) ? round(($passed / count($owaspResults)) * 100, 2) : 0;

        $summary = ['passed' => $passed, 'failed' => $failed, 'score' => $score];

        $riskScore = $this->calculateRiskScore($results);

        // Save results as array directly (casts handle JSON)
        $scan->update(array_merge($metrics, [
            'status' => 'completed',
            'completed_at' => now(),
            'risk_score' => $riskScore,
            'results' => [
                'raw_results' => $results,
                'owasp_results' => $owaspResults,
                'summary' => $summary,
            ],
        ]));

        Log::info("✅ Completed '{$depth}' scan for {$url}. Risk Score: {$riskScore} | OWASP Score: {$score}%");

        return [
            'raw_results' => $results,
            'metrics' => $metrics,
            'owasp_results' => $owaspResults,
            'summary' => $summary,
        ];
    }

    protected function calculateRiskScore(array $findings): int
    {
        $weights = ['critical'=>5,'high'=>4,'medium'=>3,'low'=>2,'info'=>1];
        $totalWeight = 0;
        foreach ($findings as $finding) {
            $severity = strtolower($finding['severity'] ?? 'low');
            $totalWeight += $weights[$severity] ?? 2;
        }

        $maxPossible = count($findings) * 5;
        return $maxPossible ? min(100, round(($totalWeight / $maxPossible) * 100)) : 0;
    }
}
