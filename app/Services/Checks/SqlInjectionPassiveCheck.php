<?php

namespace App\Services\Checks;

class SqlInjectionPassiveCheck extends BaseCheck
{
    protected array $payloads = [
        "' OR '1'='1",
        "\" OR \"1\"=\"1",
        "' OR 1=1 -- ",
        "'; DROP TABLE users; --",
    ];

    protected array $sqlErrorSignatures = [
        'you have an error in your sql syntax',
        'warning: mysql',
        'unclosed quotation mark',
        'sqlstate',
        'syntax error near',
        'pg_query',
        'mysql_fetch',
    ];

    public function run(string $url, $depth = null, $scan = null): array
    {
        $findings = [];
        $metrics = ['sql_injections_detected' => 0];

        $baseUrl = (parse_url($url, PHP_URL_QUERY)) ? $url : rtrim($url, '/') . '/?q=';

        foreach ($this->payloads as $payload) {
            $probe = $baseUrl . urlencode($payload);
            try {
                $res = $this->client->get($probe);
                $body = strtolower((string)$res->getBody());

                foreach ($this->sqlErrorSignatures as $sig) {
                    if (strpos($body, $sig) !== false) {
                        $metrics['sql_injections_detected']++;
                        $findings[] = [
                            'category' => 'A03: Injection',
                            'title' => 'Possible SQL Injection (passive)',
                            'description' => "SQL signature '{$sig}' detected.",
                            'severity' => 'high',
                            'evidence' => ['payload' => $payload],
                        ];
                        continue 2;
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return ['findings' => $findings, 'metrics' => $metrics];
    }
}
