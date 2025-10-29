<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Console\Commands\UpdateAnimeInfoCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->commands([
            UpdateAnimeInfoCommand::class,
        ]);
        
        // Define gates for authorization
        Gate::define('admin-access', function ($user) {
            return $user->is_admin;
        });
    }
}
