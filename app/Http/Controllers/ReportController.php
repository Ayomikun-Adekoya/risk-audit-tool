<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\OwaspAnalyzer;

class ReportController extends Controller
{
    /**
     * Display a listing of completed scan reports with summary stats.
     */
    public function index()
    {
        $reports = Scan::where('status', 'completed')->latest()->get();

        // Compute advanced summary based on actual vulnerability fields
        $summary = [
            'total' => $reports->count(),
            'passed' => $reports->filter(function ($r) {
                return (
                    $r->sql_injections_detected == 0 &&
                    $r->open_ports_count == 0 &&
                    $r->access_control_issues == 0 &&
                    $r->weak_passwords_detected == 0 &&
                    empty($r->ssrf_detected)
                );
            })->count(),

            'warnings' => $reports->filter(function ($r) {
                return (
                    $r->sql_injections_detected == 0 &&
                    $r->access_control_issues == 0 &&
                    $r->weak_passwords_detected == 0 &&
                    $r->ssrf_detected == 0 &&
                    $r->open_ports_count > 0
                );
            })->count(),

            'failed' => $reports->filter(function ($r) {
                return (
                    $r->sql_injections_detected > 0 ||
                    $r->access_control_issues > 0 ||
                    $r->weak_passwords_detected > 0 ||
                    $r->ssrf_detected > 0
                );
            })->count(),
        ];

        return view('reports.index', compact('reports', 'summary'));
    }

    /**
     * Show a single report with OWASP analysis.
     */
    public function show($id)
    {
        $report = Scan::findOrFail($id);
        $owaspResults = OwaspAnalyzer::analyze($report);

        // Decode JSON results if available
        $rawResults = null;
        if (!empty($report->results)) {
            try {
                $rawResults = json_decode($report->results, true);
            } catch (\Throwable $e) {
                Log::warning("Could not decode results for scan {$report->id}: " . $e->getMessage());
            }
        }

        return view('reports.show', compact('report', 'owaspResults', 'rawResults'));
    }

    /**
     * Generate and download a PDF version of the report.
     */
    public function download($id)
    {
        $report = Scan::findOrFail($id);
        $owaspResults = OwaspAnalyzer::analyze($report);

        $pdf = \PDF::loadView('reports.pdf', [
            'report' => $report,
            'owaspResults' => $owaspResults
        ])->setPaper('a4', 'portrait');

        $filename = "Security_Report_{$report->id}.pdf";

        return $pdf->download($filename);
    }
}
