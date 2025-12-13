<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class DiscordWebhookService
{
    /**
     * Send a message to the Discord webhook
     */
    public static function send(string $title, string $message, string $color = '3447003'): bool
    {
        $webhookUrl = config('services.discord.webhook_url') ?? DB::table('config')->where('key', 'discord_webhook')->value('value');
        
        if (empty($webhookUrl)) {
            return false;
        }

        try {
            $response = Http::post($webhookUrl, [
                'embeds' => [
                    [
                        'title' => $title,
                        'description' => $message,
                        'color' => hexdec($color),
                        'timestamp' => now()->toIso8601String(),
                        'footer' => [
                            'text' => config('app.name'),
                        ],
                    ],
                ],
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Discord webhook failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a ban notification
     */
    public static function sendBan(string $playerName, string $reason, string $staffName, ?string $duration = null): bool
    {
        $message = "**Player:** {$playerName}\n";
        $message .= "**Reason:** {$reason}\n";
        $message .= "**Duration:** " . ($duration ?? 'Permanent') . "\n";
        $message .= "**Banned by:** {$staffName}";
        
        return self::send('🚫 Player Banned', $message, 'FF0000');
    }

    /**
     * Send a kick notification
     */
    public static function sendKick(string $playerName, string $reason, string $staffName): bool
    {
        $message = "**Player:** {$playerName}\n";
        $message .= "**Reason:** {$reason}\n";
        $message .= "**Kicked by:** {$staffName}";
        
        return self::send('👢 Player Kicked', $message, 'FFA500');
    }

    /**
     * Send a warning notification
     */
    public static function sendWarn(string $playerName, string $reason, string $staffName): bool
    {
        $message = "**Player:** {$playerName}\n";
        $message .= "**Reason:** {$reason}\n";
        $message .= "**Warned by:** {$staffName}";
        
        return self::send('⚠️ Player Warned', $message, 'FFFF00');
    }
}
