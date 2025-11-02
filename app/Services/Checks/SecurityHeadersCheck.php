<?php

namespace App\Services\Checks;

class SecurityHeadersCheck extends BaseCheck
{
    public function run(string $url, $depth = null, $scan = null): array
    {
        $findings = [];
        $hasLogging = true;

        try {
            $res = $this->client->get($url);
        } catch (\Throwable $e) {
            return ['findings' => [], 'metrics' => ['has_logging_enabled' => false]];
        }

        $headers = array_change_key_case($res->getHeaders(), CASE_LOWER);
        $required = ['content-security-policy', 'x-frame-options', 'x-content-type-options'];

        foreach ($required as $header) {
            if (!isset($headers[$header])) {
                $findings[] = [
                    'category' => 'A05: Security Misconfiguration',
                    'title' => "Missing {$header}",
                    'severity' => 'medium',
                ];
            }
        }

        return ['findings' => $findings, 'metrics' => ['has_logging_enabled' => $hasLogging]];
    }
}
