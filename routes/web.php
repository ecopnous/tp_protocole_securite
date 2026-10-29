<?php

use App\Http\Controllers\SecurityCenterController;
use App\Http\Controllers\SecurityDeviceController;
use App\Http\Controllers\ZeroTrustSimulationController;
use App\Http\Middleware\EnsureMfaConfigured;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('security/device-verification', [SecurityDeviceController::class, 'pending'])
    ->name('security.devices.pending');
Route::get('security/devices/{challenge}/{token}/confirm', [SecurityDeviceController::class, 'confirmation'])
    ->name('security.devices.confirm');
Route::post('security/devices/{challenge}/{token}/confirm', [SecurityDeviceController::class, 'confirm']);
Route::get('security/devices/{challenge}/{token}/deny', [SecurityDeviceController::class, 'confirmation'])
    ->name('security.devices.deny');
Route::post('security/devices/{challenge}/{token}/deny', [SecurityDeviceController::class, 'deny']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [ZeroTrustSimulationController::class, 'index'])->name('dashboard');
    Route::post('dashboard/simulate', [ZeroTrustSimulationController::class, 'simulate'])->name('dashboard.simulate');

    Route::get('zero-trust', [SecurityCenterController::class, 'index'])->name('zero-trust.index');
    Route::post('zero-trust/sessions/revoke-others', [SecurityCenterController::class, 'revokeOtherSessions'])
        ->middleware('password.confirm')
        ->name('zero-trust.sessions.revoke-others');
    Route::get('zero-trust/workspace', [SecurityCenterController::class, 'workspace'])
        ->middleware([EnsureMfaConfigured::class, 'password.confirm'])
        ->name('zero-trust.workspace');
});

require __DIR__.'/settings.php';
