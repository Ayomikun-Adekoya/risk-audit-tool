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
        // Logged-in users → dashboard controller
        return app(DashboardController::class)->index();
    }

    // Guests → show home in "landing mode"
    return view('home', ['isGuest' => true]);
})->name('home');

// Guest Scan (free/public scan without login)
Route::get('/scan', [ScanController::class, 'guestScan'])->name('guest.scan');
Route::post('/scan', [ScanController::class, 'runGuestScan'])->name('guest.scan.run');

// Protected routes → only for authenticated & verified users
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Scans
    Route::resource('scans', ScanController::class);

    // Reports
    Route::resource('reports', ReportController::class);

    // Audit Logs
    Route::resource('logs', AuditLogController::class);

    // Vulnerabilities
    Route::resource('vulnerabilities', VulnerabilityController::class);
});

require __DIR__.'/auth.php';
