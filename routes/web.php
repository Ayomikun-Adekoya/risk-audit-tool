<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\VulnerabilityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page → Dashboard for logged-in users, Guest landing for others
Route::get('/', function () {
    if (Auth::check()) {
        return app(DashboardController::class)->index();
    }
    return view('home', ['isGuest' => true]);
})->name('home');


// =====================
// 🧭 Guest Scan Routes
// =====================

// Show the guest quick-scan form (reuse scans.create but limited)
Route::get('/scan', [ScanController::class, 'guestScan'])->name('guest.scan');

// Handle guest scan submission (quick scan only, queued)
Route::post('/scan/run', [ScanController::class, 'runGuestScan'])->name('guest.scan.run');


// =====================
// 🔐 Authenticated Routes
// =====================
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Scans
    Route::resource('scans', ScanController::class);

    // AJAX polling route for scan status
    Route::get('scans/{scan}/status', [ScanController::class, 'status'])->name('scans.status');

    // Reports
    Route::resource('reports', ReportController::class);

    // Audit Logs
    Route::resource('logs', AuditLogController::class);

    // Vulnerabilities
    Route::resource('vulnerabilities', VulnerabilityController::class);
    Route::get('/scans/{scan}/status', [ScanController::class, 'status'])
     ->name('scans.status');

});

require __DIR__ . '/auth.php';
