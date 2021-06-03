<?php

namespace Topdot\Admin\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-admin:make {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a CRUD: Controller, Model, Request';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $lowerName = strtolower($this->argument('name'));

        $pluralName = Str::plural($name);
        $lowerPluralName = strtolower($pluralName);

        // Create the CRUD Controller and show output
        $this->call('laravel-admin:make:controller', ['name' => $name]);

        // Create the CRUD Model and show output
        // $this->call('laravel-admin:make:model', ['name' => $name]);

        // Create the CRUD Request and show output
        // $this->call('laravel-admin:make:request', ['name' => $name]);

        /*// Create the CRUD route
        $this->call('laravel-admin:add-custom-route', [
            'code' => "Route::resource('$lowerPluralName', '{$name}Controller');",
        ]);

        // Create the sidebar item
        $this->call('laravel-admin:add-sidebar-content', [
            'code' => '<li class="nav-item {{ $menu_active == \'' . $lowerPluralName . '\' ? \'active\' : ''}}"><a href="{{ route(\'laravel-admin.' . $lowerPluralName . '.index\') }}"><i class="feather icon-archive"></i><span class="menu-title">'. $pluralName .'</span></a></li>',
        ]);*/

        // if the application uses cached routes, we should rebuild the cache so the previous added route will
        // be acessible without manually clearing the route cache.
        if (app()->routesAreCached()) {
            $this->call('route:cache');
        }
    }
}
