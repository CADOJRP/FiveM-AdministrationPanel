<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Staff members (Discord authenticated)
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('discord_id', 20)->unique();
            $table->string('discord_username');
            $table->string('discord_avatar')->nullable();
            $table->json('discord_roles')->nullable();
            $table->string('role')->default('user'); // Computed from Discord roles
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });

        // Players (from FiveM servers)
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('trust_score')->default(75);
            $table->integer('playtime_minutes')->default(0);
            $table->timestamp('first_joined_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });

        // Player identifiers (multi-ID support)
        Schema::create('player_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['fivem', 'discord', 'license2', 'license', 'steam', 'xbl', 'ip']);
            $table->string('value');
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            
            $table->unique(['type', 'value']);
            $table->index(['type', 'value']);
        });

        // Bans
        Schema::create('bans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null');
            $table->json('identifiers'); // All IDs at ban time
            $table->string('player_name'); // Cached name
            $table->text('reason');
            $table->timestamp('expires_at')->nullable(); // NULL = permanent
            $table->string('banned_by_discord_id', 20);
            $table->string('banned_by_name');
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index('active');
            $table->index('expires_at');
        });

        // Kicks
        Schema::create('kicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null');
            $table->string('player_name');
            $table->text('reason');
            $table->string('kicked_by_discord_id', 20);
            $table->string('kicked_by_name');
            $table->timestamps();
        });

        // Warnings
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null');
            $table->string('player_name');
            $table->text('reason');
            $table->string('warned_by_discord_id', 20);
            $table->string('warned_by_name');
            $table->timestamps();
        });

        // Commendations
        Schema::create('commendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null');
            $table->string('player_name');
            $table->text('reason');
            $table->string('commended_by_discord_id', 20);
            $table->string('commended_by_name');
            $table->timestamps();
        });

        // FiveM Servers
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token', 64)->unique(); // JWT token for auth
            $table->string('connection')->nullable(); // IP:Port for display
            $table->boolean('online')->default(false);
            $table->integer('player_count')->default(0);
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();
        });

        // Audit Log
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('action', ['ban', 'unban', 'kick', 'warn', 'commend', 'login', 'server_action']);
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null');
            $table->string('staff_discord_id', 20)->nullable();
            $table->string('staff_name')->nullable();
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->index('action');
            $table->index('created_at');
        });

        // Sessions (for Laravel session driver)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Panel Config
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('servers');
        Schema::dropIfExists('commendations');
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('kicks');
        Schema::dropIfExists('bans');
        Schema::dropIfExists('player_identifiers');
        Schema::dropIfExists('players');
        Schema::dropIfExists('staff');
    }
};
