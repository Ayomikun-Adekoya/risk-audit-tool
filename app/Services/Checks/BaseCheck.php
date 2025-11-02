<?php

namespace App\Services\Checks;

use GuzzleHttp\Client;

abstract class BaseCheck
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'http_errors' => false,
            'timeout' => 10,
            'verify' => false,
        ]);
    }

    protected function normalizeUrl(string $url, string $path = ''): string
    {
        return rtrim($url, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Each check should return:
     * [
     *   'findings' => [...],
     *   'metrics' => [...]
     * ]
     */
    abstract public function run(string $url, $depth = null, $scan = null): array;
}
