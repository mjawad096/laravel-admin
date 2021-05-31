<?php

namespace Topdot\Admin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Livewire;

class AdminServiceProvider extends ServiceProvider
{
    public $routeFilePath = '/routes/laravel-admin.php';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views/'), 'laravel-admin');
        $this->mergeConfigFrom(__DIR__.'/config.php', 'laravel-admin');

        $this->setupRoutes($this->app->router);

        if ($this->app->runningInConsole()) {
            $this->publishFiles();            
        }
    }



    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        if (file_exists(base_path().$this->routeFilePath)) {
            $routeFilePathInUse = base_path().$this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    public function publishFiles()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('laravel-admin.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/public' => public_path(),
        ], 'public');
    }
}
