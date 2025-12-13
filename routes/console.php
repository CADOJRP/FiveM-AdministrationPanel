<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clean up expired bans
Artisan::command('bans:cleanup', function () {
    $expired = \App\Models\Ban::where('active', true)
        ->whereNotNull('expires_at')
        ->where('expires_at', '<', now())
        ->update(['active' => false]);
    
    $this->info("Deactivated {$expired} expired bans.");
})->purpose('Deactivate expired bans');
