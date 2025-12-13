<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI', '/auth/discord/callback'),
        'bot_token' => env('DISCORD_BOT_TOKEN'),
        'guild_id' => env('DISCORD_GUILD_ID'),
        'webhook_url' => env('DISCORD_WEBHOOK_URL'),
        
        // Role IDs for permission sync
        'role_owner' => env('DISCORD_ROLE_OWNER'),
        'role_admin' => env('DISCORD_ROLE_ADMIN'),
        'role_moderator' => env('DISCORD_ROLE_MODERATOR'),
        'role_trial' => env('DISCORD_ROLE_TRIAL'),
    ],

];
