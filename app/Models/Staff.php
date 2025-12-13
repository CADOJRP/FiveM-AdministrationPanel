<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'discord_id',
        'discord_username',
        'discord_avatar',
        'discord_roles',
        'role',
        'last_login_at',
    ];

    protected $casts = [
        'discord_roles' => 'array',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->discord_avatar) {
            return "https://cdn.discordapp.com/avatars/{$this->discord_id}/{$this->discord_avatar}.png";
        }
        return "https://cdn.discordapp.com/embed/avatars/0.png";
    }

    /**
     * Check if staff has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        $rolePermissions = [
            'owner' => ['ban', 'unban', 'kick', 'warn', 'commend', 'edit_staff', 'edit_servers', 'edit_panel', 'delete_records', 'view_ips'],
            'admin' => ['ban', 'unban', 'kick', 'warn', 'commend', 'delete_records'],
            'moderator' => ['ban', 'kick', 'warn', 'commend'],
            'trial' => ['kick', 'warn', 'commend'],
            'user' => [],
        ];

        $permissions = $rolePermissions[$this->role] ?? [];
        return in_array($permission, $permissions);
    }

    /**
     * Check if user is at least a trial mod
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['owner', 'admin', 'moderator', 'trial']);
    }

    /**
     * Sync role from Discord roles
     */
    public function syncRoleFromDiscord(): void
    {
        $discordRoles = $this->discord_roles ?? [];
        
        $roleMapping = [
            config('services.discord.role_owner') => 'owner',
            config('services.discord.role_admin') => 'admin',
            config('services.discord.role_moderator') => 'moderator',
            config('services.discord.role_trial') => 'trial',
        ];

        foreach ($roleMapping as $discordRoleId => $panelRole) {
            if (in_array($discordRoleId, $discordRoles)) {
                $this->role = $panelRole;
                $this->save();
                return;
            }
        }

        $this->role = 'user';
        $this->save();
    }
}
