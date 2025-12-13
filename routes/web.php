<?php

use App\Http\Controllers\Auth\DiscordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

// Discord OAuth
Route::prefix('auth/discord')->group(function () {
    Route::get('redirect', [DiscordController::class, 'redirect'])->name('discord.redirect');
    Route::get('callback', [DiscordController::class, 'callback'])->name('discord.callback');
});

Route::post('/logout', [DiscordController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes (must be logged in)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Players
    Route::prefix('players')->name('players.')->group(function () {
        Route::get('/', [PlayerController::class, 'index'])->name('index');
        Route::get('/{player}', [PlayerController::class, 'show'])->name('show');
        
        // Actions (require specific permissions)
        Route::post('/{player}/ban', [PlayerController::class, 'ban'])->name('ban');
        Route::post('/{player}/kick', [PlayerController::class, 'kick'])->name('kick');
        Route::post('/{player}/warn', [PlayerController::class, 'warn'])->name('warn');
        Route::post('/{player}/commend', [PlayerController::class, 'commend'])->name('commend');
    });

    // Bans
    Route::delete('/bans/{ban}', [PlayerController::class, 'unban'])->name('bans.destroy');

    // Servers (admin only)
    Route::prefix('servers')->name('servers.')->middleware('can:edit_servers')->group(function () {
        Route::get('/', [\App\Http\Controllers\ServerController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\ServerController::class, 'store'])->name('store');
        Route::delete('/{server}', [\App\Http\Controllers\ServerController::class, 'destroy'])->name('destroy');
        Route::post('/{server}/regenerate-token', [\App\Http\Controllers\ServerController::class, 'regenerateToken'])->name('regenerate-token');
    });

    // Staff (admin only)
    Route::prefix('staff')->name('staff.')->middleware('can:edit_staff')->group(function () {
        Route::get('/', [\App\Http\Controllers\StaffController::class, 'index'])->name('index');
    });

    // Settings (owner only)
    Route::prefix('settings')->name('settings.')->middleware('can:edit_panel')->group(function () {
        Route::get('/', [\App\Http\Controllers\SettingsController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\SettingsController::class, 'update'])->name('update');
    });
});
