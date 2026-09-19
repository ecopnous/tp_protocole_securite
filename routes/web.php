<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware(EnsureUserIsAdmin::class)->group(function () {
        Route::view('admin', 'admin.dashboard')->name('admin.dashboard');
    });
});

require __DIR__.'/settings.php';
