<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\RunScanJob;

class ScanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scans = Scan::where('user_id', Auth::id())->latest()->get();
        return view('scans.index', compact('scans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('scans.create');
    }

    /**
     * Show the scan creation page for guests (Quick scan only).
     */
    public function guestScan()
    {
        return view('scans.create', [
            'isGuest' => true,
        ]);
    }

    /**
     * Store a newly created scan in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'target_url' => 'required|url|max:500',
            'scan_depth' => 'required|in:quick,standard,deep',
        ]);

        // Force guest scans to "quick" only
        if (!Auth::check()) {
            $validated['scan_depth'] = 'quick';
        }

        // Create scan record
        $scan = Scan::create([
            'user_id'      => Auth::id(),
            'target_url'   => $validated['target_url'],
            'scan_depth'   => $validated['scan_depth'],
            'status'       => 'pending',
            'risk_score'   => null,
            'started_at'   => null,
            'completed_at' => null,
        ]);

        // Dispatch scan job for all depths
        RunScanJob::dispatch($scan->id);

        // Redirect user immediately
        if (Auth::check()) {
            return redirect()->route('scans.show', $scan->id)
                             ->with('success', 'Scan created successfully! It is now queued.');
        }

        return redirect()->route('guest.scan')
                         ->with('success', 'Scan queued successfully!');
    }

    /**
     * Display the specified scan.
     */
    public function show(string $id)
    {
        $scan = Scan::findOrFail($id);
        return view('scans.show', compact('scan'));
    }

    /**
     * Show the form for editing the specified scan.
     */
    public function edit(string $id)
    {
        $scan = Scan::findOrFail($id);
        return view('scans.edit', compact('scan'));
    }

    /**
     * Update the specified scan in storage.
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

        return redirect()->route('scans.show', $scan->id)
                         ->with('success', 'Scan updated successfully.');
    }

    /**
     * Remove the specified scan from storage.
     */
    public function destroy(string $id)
    {
        $scan = Scan::findOrFail($id);
        $scan->delete();

        return redirect()->route('scans.index')
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
    