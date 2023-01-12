<?php

namespace DmLogic\CommunityContributions\Providers;

use Illuminate\Support\ServiceProvider;

class CommunityContributionsProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerRoutes();
        $this->setConfigValues();
        $this->registerMigrations();
    }

    private function registerCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
    }

    private function setConfigValues(): void
    {
        // Allows values to be picked up from .env
        $this->mergeConfigFrom(
            __DIR__ . '/../config/community-contributions.php',
            'community'
        );

        // Ensure we have a full path to our sqlite database
        config(['database.connections.sqlite.database' => database_path('database.sqlite')]);
    }

    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
