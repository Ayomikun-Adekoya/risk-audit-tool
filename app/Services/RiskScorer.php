<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class RiskScorer
{
    /**
     * Base numeric values assigned to each severity level.
     */
    protected array $severityWeights = [
        'info'     => 1.0,
        'low'      => 2.5,
        'medium'   => 5.0,
        'high'     => 8.0,
        'critical' => 9.5,
    ];

    /**
     * Compute a normalized overall risk score (0–10) from findings.
     *
     * @param  array  $findings  Array of vulnerability findings, each containing:
     *                           - severity: string ("low", "medium", "high", "critical")
     *                           - requires_auth: bool (optional)
     *                           - vector: string ("network" | "local" | etc.)
     * @param  string $mode      Calculation mode: "max" (default) or "average"
     * @return float
     */
    public function score(array $findings, string $mode = 'max'): float
    {
        if (empty($findings)) {
            return 0.0;
        }

        $scores = array_map(function ($finding) {
            try {
                $severity = strtolower($finding['severity'] ?? 'medium');
                $base = $this->severityWeights[$severity] ?? 5.0;
                $multiplier = 1.0;

                // Authenticated issues get reduced weight
                if (!empty($finding['requires_auth'])) {
                    $multiplier *= 0.6;
                }

                // Network-based issues are slightly riskier
                if (($finding['vector'] ?? '') === 'network') {
                    $multiplier *= 1.1;
                }

                return min(10.0, round($base * $multiplier, 1));
            } catch (\Throwable $e) {
                Log::warning('⚠️ RiskScorer: error scoring finding', [
                    'finding' => $finding,
                    'error'   => $e->getMessage(),
                ]);
                return 0.0;
            }
        }, $findings);

        if ($mode === 'average') {
            $avg = array_sum($scores) / max(count($scores), 1);
            return round($avg, 1);
        }

        // Default: emphasize the worst vulnerability
        return (float) max($scores);
    }

    /**
     * Provide a simple qualitative label for the computed score.
     *
     * @param  float  $score
     * @return string
     */
    public function label(float $score): string
    {
        return match (true) {
            $score < 2   => 'Informational',
            $score < 4   => 'Low Risk',
            $score < 6.5 => 'Medium Risk',
            $score < 8.5 => 'High Risk',
            default       => 'Critical Risk',
        };
    }
}
