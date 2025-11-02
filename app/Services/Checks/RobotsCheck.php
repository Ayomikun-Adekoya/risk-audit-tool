<?php

namespace App\Services\Checks;

class RobotsCheck extends BaseCheck
{
    public function run(string $url, $depth = null, $scan = null): array
    {
        $probe = $this->normalizeUrl($url, '/robots.txt');
        $findings = [];
        $ssrfDetected = false;

        try {
            $res = $this->client->get($probe);
            if ($res->getStatusCode() === 200) {
                $body = strtolower((string)$res->getBody());
                if (strpos($body, 'admin') !== false || strpos($body, 'private') !== false) {
                    $ssrfDetected = true;
                    $findings[] = [
                        'category' => 'A10: SSRF',
                        'title' => 'robots.txt exposes sensitive paths',
                        'severity' => 'low',
                    ];
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return ['findings' => $findings, 'metrics' => ['ssrf_detected' => $ssrfDetected]];
    }
}
