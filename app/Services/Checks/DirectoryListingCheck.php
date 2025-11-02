<?php

namespace App\Services\Checks;

class DirectoryListingCheck extends BaseCheck
{
    protected array $paths = ['/', '/uploads/', '/storage/', '/assets/'];

    public function run(string $url, $depth = null, $scan = null): array
    {
        $findings = [];
        $issues = 0;

        foreach ($this->paths as $path) {
            $probe = $this->normalizeUrl($url, $path);
            try {
                $res = $this->client->get($probe);
                $body = strtolower((string)$res->getBody());

                if (strpos($body, 'index of') !== false) {
                    $issues++;
                    $findings[] = [
                        'category' => 'A05: Security Misconfiguration',
                        'title' => 'Directory Listing Enabled',
                        'severity' => 'high',
                        'evidence' => ['url' => $probe],
                    ];
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return ['findings' => $findings, 'metrics' => ['access_control_issues' => $issues]];
    }
}
