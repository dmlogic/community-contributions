<?php

namespace DmLogic\CommunityContributions\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        // $this->registerCommands();
        // $this->registerRoutes();
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
        Factory::guessFactoryNamesUsing(function ($name) {
            return (string) "\Database\Factories\\".
                (class_basename($name)).
                'Factory';
        });

        // Allows values to be picked up from .env
        $this->mergeConfigFrom(
            __DIR__ . '/../config/community-contributions.php',
            'community'
        );

        // UK please
        config(['app.locale' => 'en_GB']);
        config(['app.faker_locale' => 'en_GB']);

        // Ensure we have a full path to our sqlite database
        config(['database.connections.sqlite.database' => database_path('database.sqlite')]);
    }

    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
