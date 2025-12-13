<?php

namespace App\Providers;

use App\Models\Staff;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define permission gates
        Gate::define('ban', fn (Staff $user) => $user->hasPermission('ban'));
        Gate::define('unban', fn (Staff $user) => $user->hasPermission('unban'));
        Gate::define('kick', fn (Staff $user) => $user->hasPermission('kick'));
        Gate::define('warn', fn (Staff $user) => $user->hasPermission('warn'));
        Gate::define('commend', fn (Staff $user) => $user->hasPermission('commend'));
        Gate::define('edit_staff', fn (Staff $user) => $user->hasPermission('edit_staff'));
        Gate::define('edit_servers', fn (Staff $user) => $user->hasPermission('edit_servers'));
        Gate::define('edit_panel', fn (Staff $user) => $user->hasPermission('edit_panel'));
        Gate::define('delete_records', fn (Staff $user) => $user->hasPermission('delete_records'));
        Gate::define('view_ips', fn (Staff $user) => $user->hasPermission('view_ips'));
    }
}
