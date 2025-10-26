<?php

namespace App\Services;

class ScannerService
{
    /**
     * Checks available for different scan depths.
     * You can expand these as you build more check classes.
     */
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
        ],
    ];

    /**
     * Run appropriate checks based on scan depth.
     *
     * @param string $url  Target URL to scan
     * @param string|int $depth  Depth type: 'quick', 'standard', 'deep' (or numeric)
     * @param mixed $scan  Optional Scan model instance for tracking
     * @return array
     */
    public function run(string $url, string|int $depth, $scan = null): array
    {
        // Normalize numeric depths to names if job passed integers
        if (is_numeric($depth)) {
            $depth = match ((int) $depth) {
                1 => 'quick',
                2 => 'standard',
                3 => 'deep',
                default => 'quick',
            };
        }

        // Determine which checks to run for this scan type
        $checks = $this->depthChecks[$depth] ?? $this->depthChecks['quick'];

        \Log::info("🔍 Starting {$depth} scan for {$url} with " . count($checks) . " checks.");

        $results = [];

        foreach ($checks as $checkClass) {
            if (!class_exists($checkClass)) {
                \Log::warning("⚠️ Missing check class: {$checkClass}");
                continue;
            }

            $checker = app($checkClass);

            try {
                $r = $checker->run($url, $depth, $scan);
                if (is_array($r) && count($r)) {
                    $results = array_merge($results, $r);
                }
            } catch (\Throwable $e) {
                \Log::warning("❌ Checker {$checkClass} failed: " . $e->getMessage());
            }
        }

        \Log::info("✅ Completed {$depth} scan for {$url}. Findings: " . count($results));

        return $results;
    }
}
