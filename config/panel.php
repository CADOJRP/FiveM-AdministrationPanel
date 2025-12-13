<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Panel Configuration
    |--------------------------------------------------------------------------
    */

    'trust_score' => [
        'base' => env('TRUST_SCORE_BASE', 75),
        'warn_penalty' => env('TRUST_SCORE_WARN_PENALTY', 3),
        'kick_penalty' => env('TRUST_SCORE_KICK_PENALTY', 6),
        'ban_penalty' => env('TRUST_SCORE_BAN_PENALTY', 10),
        'commend_bonus' => env('TRUST_SCORE_COMMEND_BONUS', 2),
    ],

];
