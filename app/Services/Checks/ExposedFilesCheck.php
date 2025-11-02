<?php

namespace App\Services\Checks;

class ExposedFilesCheck extends BaseCheck
{
    protected array $files = [
        '/.env',
        '/.git/config',
        '/composer.json',
        '/package.json',
        '/README.md',
    ];

    public function run(string $url, $depth = null, $scan = null): array
    {
        $findings = [];
        $count = 0;

        foreach ($this->files as $file) {
            $probe = $this->normalizeUrl($url, $file);
            try {
                $res = $this->client->get($probe);
                $status = $res->getStatusCode();
                $body = strtolower((string)$res->getBody());

                if ($status === 200 && strpos($body, 'app_key') !== false) {
                    $count++;
                    $findings[] = [
                        'category' => 'A05: Security Misconfiguration',
                        'title' => "Exposed File {$file}",
                        'severity' => 'critical',
                        'evidence' => ['url' => $probe],
                    ];
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return ['findings' => $findings, 'metrics' => ['open_ports_count' => $count]];
    }
}
