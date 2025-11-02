<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\RunScanJob;
use App\Services\OwaspAnalyzer;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    /**
     * Display a listing of the user's scans.
     */
    public function index()
    {
        $scans = Scan::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('scans.index', compact('scans'));
    }

    /**
     * Show the form for creating a new scan.
     */
    public function create()
    {
        return view('scans.create');
    }

    /**
     * Show quick scan form for guests.
     */
    public function guestScan()
    {
        return view('scans.create', ['isGuest' => true]);
    }

    /**
     * Store a newly created scan.
     */
    public function store(Request $request)
    {
        // ✅ Validate input including consent (using consent_given to match your form)
        $validated = $request->validate([
            'target_url'    => 'required|url|max:500',
            'scan_depth'    => 'required|in:quick,standard,deep',
            'consent_given' => 'accepted', // ✅ matches your form name
        ], [
            'consent_given.accepted' => 'You must confirm that you have permission to scan the target.',
        ]);

        // ✅ Force guest scans to quick only
        if (!Auth::check()) {
            $validated['scan_depth'] = 'quick';
        }

        // ✅ Create new scan entry with explicit consent flag
        $scan = Scan::create([
            'user_id'        => Auth::id(),
            'target_url'     => $validated['target_url'],
            'scan_depth'     => $validated['scan_depth'],
            'status'         => 'pending',
            'risk_score'     => null,
            'started_at'     => null,
            'completed_at'   => null,
            'consent_given'  => 1,               // ✅ boolean column
            'consent_ip'     => $request->ip(),  // optional IP tracking
        ]);

        // ✅ Log consent for audit
        Log::info('Scan consent recorded', [
            'scan_id' => $scan->id,
            'user_id' => Auth::id(),
            'ip'      => $request->ip(),
            'target'  => $scan->target_url,
        ]);

        // ✅ Dispatch scan job only if consent is true
        if ($scan->consent_given) {
            RunScanJob::dispatch($scan->id);
            $scan->update(['status' => 'running']);
            Log::info("Scan {$scan->id} queued successfully.");
        } else {
            Log::warning("Scan {$scan->id} missing consent — job not dispatched.");
            $scan->update(['status' => 'failed']);
        }

        // ✅ Redirect based on user type
        if (Auth::check()) {
            return redirect()
                ->route('scans.show', $scan->id)
                ->with('success', 'Scan created successfully and is now queued.');
        }

        return redirect()
            ->route('guest.scan')
            ->with('success', 'Scan queued successfully!');
    }

    /**
     * Display the specified scan and run OWASP summary if completed.
     */
    public function show(string $id)
    {
        $scan = Scan::findOrFail($id);

        $analysis = [];
        $summary = [
            'passed'          => 0,
            'failed'          => 0,
            'unknown'         => 0,
            'recommendations' => [],
        ];

        // ✅ Run analysis only when scan is completed
        if ($scan->status === 'completed') {
            $analysis = OwaspAnalyzer::analyze($scan);

            foreach ($analysis as $a) {
                switch ($a['status']) {
                    case 'Passed':
                        $summary['passed']++;
                        break;
                    case 'Failed':
                        $summary['failed']++;
                        $summary['recommendations'][] = $a['recommendation'];
                        break;
                    default:
                        $summary['unknown']++;
                        break;
                }
            }
        }

        return view('scans.show', compact('scan', 'analysis', 'summary'));
    }

    /**
     * Edit a scan.
     */
    public function edit(string $id)
    {
        $scan = Scan::findOrFail($id);
        return view('scans.edit', compact('scan'));
    }

    /**
     * Update a scan.
     */
    public function update(Request $request, string $id)
    {
        $scan = Scan::findOrFail($id);

        $validated = $request->validate([
            'target_url' => 'required|url|max:500',
            'scan_depth' => 'required|in:quick,standard,deep',
            'status'     => 'required|in:pending,running,completed,failed',
        ]);

        $scan->update($validated);

        return redirect()
            ->route('scans.show', $scan->id)
            ->with('success', 'Scan updated successfully.');
    }

    /**
     * Delete a scan.
     */
    public function destroy(string $id)
    {
        $scan = Scan::findOrFail($id);
        $scan->delete();

        return redirect()
            ->route('scans.index')
            ->with('success', 'Scan deleted successfully.');
    }

    /**
     * Return scan status for AJAX polling.
     */
    public function status(Scan $scan)
    {
        return response()->json([
            'status'       => $scan->status,
            'risk_score'   => $scan->risk_score,
            'completed_at' => $scan->completed_at,
        ]);
    }
}
