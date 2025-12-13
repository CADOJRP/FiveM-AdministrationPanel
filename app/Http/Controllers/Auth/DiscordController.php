<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use SocialiteProviders\Discord\Provider as DiscordProvider;

class DiscordController extends Controller
{
    /**
     * Get Discord provider instance
     */
    private function getDiscordProvider(): DiscordProvider
    {
        $config = config('services.discord');
        $provider = new DiscordProvider(
            request(),
            $config['client_id'],
            $config['client_secret'],
            $config['redirect']
        );
        return $provider;
    }

    /**
     * Redirect to Discord OAuth
     */
    public function redirect(): RedirectResponse
    {
        return $this->getDiscordProvider()
            ->scopes(['identify', 'guilds.members.read'])
            ->redirect();
    }

    /**
     * Handle Discord OAuth callback
     */
    public function callback(): RedirectResponse
    {
        try {
            $discordUser = $this->getDiscordProvider()->user();
            
            // Get user's roles in the configured guild
            $guildRoles = $this->getGuildMemberRoles(
                $discordUser->token,
                config('services.discord.guild_id')
            );

            // Find or create staff record
            $staff = Staff::updateOrCreate(
                ['discord_id' => $discordUser->id],
                [
                    'discord_username' => $discordUser->nickname ?? $discordUser->name,
                    'discord_avatar' => $discordUser->avatar,
                    'discord_roles' => $guildRoles,
                    'last_login_at' => now(),
                ]
            );

            // Sync role from Discord
            $staff->syncRoleFromDiscord();

            // Check if user has staff role
            if (!$staff->isStaff()) {
                return redirect()->route('login')
                    ->with('error', 'You do not have permission to access the staff panel.');
            }

            // Log the login
            AuditLog::logAction('login', null, $staff->discord_id, $staff->discord_username);

            // Log in the user
            Auth::login($staff);
            session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . $staff->discord_username . '!');

        } catch (\Exception $e) {
            report($e);
            return redirect()->route('login')
                ->with('error', 'Failed to authenticate with Discord. Please try again.');
        }
    }

    /**
     * Get user's roles in a specific guild
     */
    private function getGuildMemberRoles(string $token, string $guildId): array
    {
        try {
            $response = Http::withToken($token)
                ->get("https://discord.com/api/v10/users/@me/guilds/{$guildId}/member");

            if ($response->successful()) {
                return $response->json('roles', []);
            }
        } catch (\Exception $e) {
            report($e);
        }

        return [];
    }

    /**
     * Logout
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out.');
    }
}
