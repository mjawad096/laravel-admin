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
    protected $description = 'Create a CRUD: Model, Request, Controller';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        // Create the Model and show output
        $this->call('make:model', ['name' => $name, '--migration' => true]);

        // Create the Request and show output
        $this->call('laravel-admin:make:request', ['name' => $name]);

        // Create the CRUD Controller and show output
        $this->call('laravel-admin:make:controller', ['name' => $name]);
    }
}
