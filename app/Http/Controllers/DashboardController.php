<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Scan;

class DashboardController extends Controller
{
    public function index()
    {
        // If user is authenticated, show personalized dashboard data
        if (Auth::check()) {
            $user = Auth::user();

            // Basic scan statistics
            $totalScans = Scan::where('user_id', $user->id)->count();
            $completedScans = Scan::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();

            // Average risk score
            $averageRisk = Scan::where('user_id', $user->id)
                ->whereNotNull('risk_score')
                ->avg('risk_score');

            // Total detected vulnerabilities across scans
            $totalVulnerabilities =
                Scan::where('user_id', $user->id)->sum('sql_injections_detected') +
                Scan::where('user_id', $user->id)->sum('open_ports_count') +
                Scan::where('user_id', $user->id)->sum('access_control_issues') +
                Scan::where('user_id', $user->id)->sum('weak_passwords_detected');

            // System health score (basic formula)
            $health = $totalScans > 0
                ? round((1 - ($totalVulnerabilities / max($totalScans * 10, 1))) * 100)
                : 100;

            // Ensure formatted values
            $averageRisk = round($averageRisk ?? 0, 2);

            return view('home', compact(
                'totalScans',
                'completedScans',
                'averageRisk',
                'totalVulnerabilities',
                'health'
            ));
        }

        // If guest, show public landing page
        return view('home');
    }
}
