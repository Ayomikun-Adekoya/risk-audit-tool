<?php

namespace App\Services\Checks;

use GuzzleHttp\Client;

class SecurityHeadersCheck
{
    public function run(string $url, $depth = null, $scan = null): array
    {
        $client = new Client(['http_errors' => false, 'verify' => false, 'timeout' => 15]);
        try {
            $res = $client->get($url);
        } catch (\Throwable $e) {
            // network error or unreachable - no findings here
            return [];
        }

        $headers = array_change_key_case($res->getHeaders(), CASE_LOWER);
        $findings = [];

        $wanted = [
            'content-security-policy',
            'x-frame-options',
            'x-content-type-options',
            'strict-transport-security'
        ];

        foreach ($wanted as $h) {
            if (!isset($headers[$h])) {
                $findings[] = [
                    'category' => 'A05: Security Misconfiguration',
                    'title' => "Missing header: {$h}",
                    'description' => "The response does not contain the {$h} header. Configure server/app to add it.",
                    'severity' => 'medium',
                    'evidence' => ['present_headers' => array_keys($headers)],
                ];
            }
        }

        // cookie flags
        if (isset($headers['set-cookie'])) {
            foreach ($headers['set-cookie'] as $cookieLine) {
                if (stripos($cookieLine, 'httponly') === false) {
                    $findings[] = [
                        'category' => 'A07: Identification & Auth Failures',
                        'title' => 'Cookie missing HttpOnly flag',
                        'description' => 'Set the HttpOnly flag on cookies to mitigate XSS session hijacking risks.',
                        'severity' => 'low',
                        'evidence' => ['cookie' => $cookieLine],
                    ];
                }
            }
        }

        return $findings;
    }
}
