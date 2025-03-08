<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Definiere Gates für die Benutzerverwaltung
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole('Admin');
        });

        // Definiere Gates für die Rollenverwaltung
        Gate::define('manage-roles', function (User $user) {
            return $user->hasRole('Admin');
        });

        // Definiere Gates für die Modulverwaltung
        Gate::define('manage-modules', function (User $user) {
            return $user->hasRole('Admin');
        });
    }
}
