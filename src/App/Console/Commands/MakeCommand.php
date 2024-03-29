<?php

namespace Dotlogics\Admin\App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Create a CRUD resources: Model, Request, Controller and Migration';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        // Create the CRUD Controller and show output
        $this->call('laravel-admin:make:controller', ['name' => $name]);

        // Create the Request and show output
        $this->call('laravel-admin:make:request', ['name' => $name]);

        // Create the Model and show output
        $this->call('make:model', ['name' => $name, '--migration' => true]);
    }
}
