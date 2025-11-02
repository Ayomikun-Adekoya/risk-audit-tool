<?php

namespace App\Services\Checks;

class TlsCheck extends BaseCheck
{
    public function run(string $url, $depth = null, $scan = null): array
    {
        $findings = [];
        $metrics = ['uses_https' => false];

        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return ['findings' => [], 'metrics' => $metrics];
        }

        $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $client = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context);

        if (!$client) {
            $findings[] = [
                'category' => 'A05: Security Misconfiguration',
                'title' => 'TLS Connection Failed',
                'description' => "Could not establish SSL/TLS connection to $host.",
                'severity' => 'high',
            ];
            return ['findings' => $findings, 'metrics' => $metrics];
        }

        $metrics['uses_https'] = true;

        $params = stream_context_get_params($client);
        $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);

        if ($cert) {
            $validTo = $cert['validTo_time_t'] ?? null;
            if ($validTo && ($validTo - time()) / 86400 < 30) {
                $findings[] = [
                    'category' => 'A06: Vulnerable & Outdated Components',
                    'title' => 'Certificate Expiring Soon',
                    'severity' => 'medium',
                ];
            }
        }

        return ['findings' => $findings, 'metrics' => $metrics];
    }
}
