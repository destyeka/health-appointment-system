<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Define who can manage queue (doctors and admin)
        Gate::define('manage-queue', function ($user) {
            return in_array(strtolower($user->role->role_name), ['doctor', 'admin']);
        });
    }
}