<?php

namespace Topdot\Admin;

// use Topdot\Admin\App\Http\Middleware\Authenticate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Livewire;
use Blade;

class AdminServiceProvider extends ServiceProvider
{

    protected $commands = [
        App\Console\Commands\Install::class,
        App\Console\Commands\PublishView::class,
        App\Console\Commands\CreateDefaultAdminUser::class,
        App\Console\Commands\MakeRequestCommand::class,
        App\Console\Commands\MakeControllerCommand::class,
        App\Console\Commands\MakeCommand::class,
    ];

    public $routeFilePath = '/routes/laravel-admin/main.php';
    public $customRouteFilePath = '/routes/laravel-admin/custom.php';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register the helper functions
        $this->loadHelpers();

        // register the artisan commands
        $this->commands($this->commands);
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

        $this->registerMiddlewareGroup($this->app->router);
        $this->setupRoutes($this->app->router);

        if ($this->app->runningInConsole()) {
            $this->publishFiles();            
        }

        Blade::directive('pushonce', function ($expression) {
            $var = '$__env->{"__pushonce_" . md5(__FILE__ . ":" . __LINE__)}';

            return "<?php if(!isset({$var})): {$var} = true; \$__env->startPush({$expression}); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });
    }

    /**
     * Load the helper methods, for convenience.
     */
    public function loadHelpers()
    {
        require_once __DIR__.'/helpers.php';
    }

    public function registerMiddlewareGroup(Router $router)
    {
        $middleware_key = config('laravel-admin.router.middleware_key', 'laravel-admin');
        $middleware_class = config('laravel-admin.router.middleware_class', []);

        if (! is_array($middleware_class)) {
            $middleware_class = [$middleware_class];
        }

        foreach ($middleware_class as $class) {
            $router->pushMiddlewareToGroup($middleware_key, $class);
        }

        // $router->aliasMiddleware('auth', Authenticate::class);
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
        if (file_exists(base_path().$this->routeFilePath)) {
            $this->loadRoutesFrom(base_path().$this->routeFilePath);
        }else{
            $this->loadRoutesFrom(__DIR__.$this->routeFilePath);
        }

        if (file_exists(base_path().$this->customRouteFilePath)) {
            $this->loadRoutesFrom(base_path().$this->customRouteFilePath);
        }else{
            $this->loadRoutesFrom(__DIR__.$this->customRouteFilePath);
        }

    }

    public function publishFiles()
    {
        $config_files = [ __DIR__.'/config.php' => config_path('laravel-admin.php'), ];
        $public_assets = [  __DIR__.'/public' => public_path(), ];
        $routes = [  __DIR__.$this->customRouteFilePath => base_path($this->customRouteFilePath), ];
        $views = [
            __DIR__.'/resources/views/inc/sidebar-menu.blade.php' => resource_path('views/vendor/laravel-admin/inc/sidebar-menu.blade.php'),
            __DIR__.'/resources/views/inc/user-menu.blade.php' => resource_path('views/vendor/laravel-admin/inc/user-menu.blade.php'),
        ];

        $this->publishes($config_files, 'config');
        $this->publishes($public_assets, 'public');
        $this->publishes($views, 'views');
        $this->publishes($routes, 'routes');

        $minimum = array_merge(            
            $config_files,
            $public_assets,
            $views,
            $routes,
        );
        $this->publishes($minimum, 'minimum');
    }
}
