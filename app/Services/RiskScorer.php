<?php

namespace App\Services;

class RiskScorer
{
    protected $map = [
        'low' => 2.5,
        'medium' => 5.0,
        'high' => 8.0,
        'critical' => 9.5,
    ];

    public function score(array $findings): float
    {
        if (empty($findings)) return 0.0;

        $scores = array_map(function ($f) {
            $severity = $f['severity'] ?? 'medium';
            $base = $this->map[$severity] ?? 5.0;
            $mult = 1.0;
            if (!empty($f['requires_auth'])) $mult *= 0.6;
            if (($f['vector'] ?? '') === 'network') $mult *= 1.1;
            return min(10.0, round($base * $mult, 1));
        }, $findings);

        // choose a strategy: max highlights the worst finding
        return (float) max($scores);
    }
}
