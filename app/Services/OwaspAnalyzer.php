<?php

namespace App\Services;

class OwaspAnalyzer
{
    /**
     * Perform OWASP Top 10 analysis based on actual scan metrics.
     *
     * @param  \App\Models\Scan|object  $scan
     * @return array
     */
    public static function analyze($scan): array
    {
        // Normalize input for consistent property access
        $metrics = is_object($scan) ? $scan : (object) $scan;

        return [
            // 🔹 A01: Broken Access Control
            'A01' => [
                'category'       => 'A01',
                'title'          => 'Broken Access Control',
                'status'         => ($metrics->access_control_issues ?? 0) > 0 ? 'Failed' : 'Passed',
                'severity'       => ($metrics->access_control_issues ?? 0) > 2 ? 'high' : 'medium',
                'recommendation' => ($metrics->access_control_issues ?? 0) > 0
                    ? 'Restrict unauthorized access by validating user roles and permissions on every request.'
                    : 'Access control appears properly enforced.',
            ],

            // 🔹 A02: Cryptographic Failures
            'A02' => [
                'category'       => 'A02',
                'title'          => 'Cryptographic Failures',
                'status'         => ($metrics->uses_https ?? false) ? 'Passed' : 'Failed',
                'severity'       => ($metrics->uses_https ?? false) ? 'low' : 'high',
                'recommendation' => ($metrics->uses_https ?? false)
                    ? 'Encryption and secure communication protocols are in place.'
                    : 'Use HTTPS and encrypt sensitive data both in storage and transit.',
            ],

            // 🔹 A03: Injection
            'A03' => [
                'category'       => 'A03',
                'title'          => 'Injection',
                'status'         => ($metrics->sql_injections_detected ?? 0) > 0 ? 'Failed' : 'Passed',
                'severity'       => ($metrics->sql_injections_detected ?? 0) > 1 ? 'high' : 'medium',
                'recommendation' => ($metrics->sql_injections_detected ?? 0) > 0
                    ? 'Use parameterized queries and sanitize user input to prevent injection attacks.'
                    : 'No injection vulnerabilities detected.',
            ],

            // 🔹 A04: Insecure Design
            'A04' => [
                'category'       => 'A04',
                'title'          => 'Insecure Design',
                'status'         => 'Unknown',
                'severity'       => 'low',
                'recommendation' => 'Review business logic and implement security controls early in the design phase.',
            ],

            // 🔹 A05: Security Misconfiguration
            'A05' => [
                'category'       => 'A05',
                'title'          => 'Security Misconfiguration',
                'status'         => ($metrics->open_ports_count ?? 0) > 0 ? 'Failed' : 'Passed',
                'severity'       => ($metrics->open_ports_count ?? 0) > 3 ? 'high' : 'medium',
                'recommendation' => ($metrics->open_ports_count ?? 0) > 0
                    ? 'Review server configuration, disable debug mode, and close unnecessary ports.'
                    : 'Server configuration appears secure.',
            ],

            // 🔹 A06: Vulnerable and Outdated Components
            'A06' => [
                'category'       => 'A06',
                'title'          => 'Vulnerable and Outdated Components',
                'status'         => 'Unknown',
                'severity'       => 'medium',
                'recommendation' => 'Keep server software and dependencies up to date using automated scans.',
            ],

            // 🔹 A07: Identification & Authentication Failures
            'A07' => [
                'category'       => 'A07',
                'title'          => 'Identification and Authentication Failures',
                'status'         => ($metrics->weak_passwords_detected ?? 0) > 0 ? 'Failed' : 'Passed',
                'severity'       => ($metrics->weak_passwords_detected ?? 0) > 0 ? 'high' : 'low',
                'recommendation' => ($metrics->weak_passwords_detected ?? 0) > 0
                    ? 'Enforce strong password policies and enable MFA where possible.'
                    : 'Authentication mechanisms appear strong.',
            ],

            // 🔹 A08: Software and Data Integrity Failures
            'A08' => [
                'category'       => 'A08',
                'title'          => 'Software and Data Integrity Failures',
                'status'         => 'Unknown',
                'severity'       => 'low',
                'recommendation' => 'Implement code signing, integrity checks, and trusted CI/CD pipelines.',
            ],

            // 🔹 A09: Security Logging & Monitoring Failures
            'A09' => [
                'category'       => 'A09',
                'title'          => 'Security Logging and Monitoring Failures',
                'status'         => ($metrics->has_logging_enabled ?? false) ? 'Passed' : 'Failed',
                'severity'       => ($metrics->has_logging_enabled ?? false) ? 'low' : 'medium',
                'recommendation' => ($metrics->has_logging_enabled ?? false)
                    ? 'Logging and monitoring mechanisms are active.'
                    : 'Implement centralized logging, monitoring, and alerting for suspicious activities.',
            ],

            // 🔹 A10: Server-Side Request Forgery (SSRF)
            'A10' => [
                'category'       => 'A10',
                'title'          => 'Server-Side Request Forgery (SSRF)',
                'status'         => ($metrics->ssrf_detected ?? false) ? 'Failed' : 'Passed',
                'severity'       => ($metrics->ssrf_detected ?? false) ? 'high' : 'low',
                'recommendation' => ($metrics->ssrf_detected ?? false)
                    ? 'Validate and sanitize all URLs fetched by the server. Restrict internal network access.'
                    : 'No SSRF vulnerabilities detected.',
            ],
        ];
    }
}
