<?php

namespace App\Services\Checks;

class TlsCheck
{
    public function run(string $url, $depth = null, $scan = null): array
    {
        $findings = [];

        // Parse host from URL
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return [[
                'category' => 'A05: Security Misconfiguration',
                'title' => 'Invalid URL',
                'description' => 'The target URL could not be parsed.',
                'severity' => 'medium',
            ]];
        }

        $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $client = @stream_socket_client(
            "ssl://{$host}:443",
            $errno,
            $errstr,
            10,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$client) {
            $findings[] = [
                'category' => 'A05: Security Misconfiguration',
                'title' => 'TLS Connection Failed',
                'description' => "Could not establish SSL/TLS connection to $host: $errstr",
                'severity' => 'high',
            ];
            return $findings;
        }

        $params = stream_context_get_params($client);
        $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);

        if (!$cert) {
            $findings[] = [
                'category' => 'A05: Security Misconfiguration',
                'title' => 'Invalid Certificate',
                'description' => 'The SSL/TLS certificate could not be parsed.',
                'severity' => 'high',
            ];
            return $findings;
        }

        // Check expiration date
        $validTo = $cert['validTo_time_t'] ?? null;
        if ($validTo) {
            $daysLeft = ($validTo - time()) / 86400;
            if ($daysLeft < 0) {
                $findings[] = [
                    'category' => 'A06: Vulnerable & Outdated Components',
                    'title' => 'Expired SSL Certificate',
                    'description' => 'The SSL/TLS certificate has expired.',
                    'severity' => 'high',
                ];
            } elseif ($daysLeft < 30) {
                $findings[] = [
                    'category' => 'A06: Vulnerable & Outdated Components',
                    'title' => 'SSL Certificate Expiring Soon',
                    'description' => "Certificate expires in about " . round($daysLeft) . " days.",
                    'severity' => 'medium',
                ];
            }
        }

        // Check issuer (self-signed)
        if (!empty($cert['issuer']) && !empty($cert['subject']) && $cert['issuer'] === $cert['subject']) {
            $findings[] = [
                'category' => 'A05: Security Misconfiguration',
                'title' => 'Self-Signed Certificate',
                'description' => 'The certificate appears to be self-signed.',
                'severity' => 'high',
            ];
        }

        return $findings;
    }
}
