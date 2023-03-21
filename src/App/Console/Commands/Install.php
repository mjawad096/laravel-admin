<?php

namespace Dotlogics\Admin\App\Console\Commands;

use Illuminate\Console\Command;
use Str;

class Install extends Command
{
	use Traits\PrettyCommandOutput;

	protected $progressBar;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'laravel-admin:install
								{--timeout=300} : How many seconds to allow each process to run.
								{--debug} : Show process output or not. Useful for debugging.';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install Laravel admin requirements on dev, publish files and create uploads directory.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed Command-line output
	 */
	public function handle()
	{
		$this->progressBar = $this->output->createProgressBar(4);
		$this->progressBar->minSecondsBetweenRedraws(0);
		$this->progressBar->maxSecondsBetweenRedraws(120);
		$this->progressBar->setRedrawFrequency(1);

		$this->progressBar->start();

		$this->info(' Admin installation started. Please wait...');
		$this->progressBar->advance();

		$this->line(' Publishing configs, views, js and css files');
		$this->executeArtisanProcess('vendor:publish', [
			'--provider' => 'Dotlogics\Admin\AdminServiceProvider',
			'--tag' => 'minimum',
		]);

		$this->line(' Publishing migrations for dependencies');
		
		if(
			! collect(glob(database_path('migrations/*.php')))
				->some(fn($migration) => Str::endsWith($migration, '_create_media_table.php'))
		){
			$this->executeArtisanProcess('vendor:publish', [
				'--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
				'--tag' => 'migrations',
			]);	
		}

		$this->executeArtisanProcess('vendor:publish', [
			'--provider' => 'Akaunting\Setting\Provider',
			'--tag' => 'setting',
		]);	
		
		$this->line(" Migrating database");
		try{
			$this->executeArtisanProcess('migrate');
		}catch(\Throwable $ex){
			$this->error(' ' . $ex->getMessage());
		}	

		$this->progressBar->finish();
		$this->info(' Admin installation finished.');
	}
}