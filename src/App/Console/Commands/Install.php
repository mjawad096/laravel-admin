<?php

namespace Topdot\Admin\App\Console\Commands;

use Illuminate\Console\Command;

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
			'--provider' => 'Topdot\Admin\AdminServiceProvider',
			'--tag' => 'minimum',
		]);

		$this->line(' Publishing migrations for dependencies');
		foreach (glob(database_path('migrations/*.php')) as $migration) include_once $migration;
		
		if(!class_exists('CreateMediaTable')){
			$this->executeArtisanProcess('vendor:publish', [
				'--provider' => 'Topdot\Media\MediaServiceProvider',
				'--tag' => 'migrations',
			]);
		}

		if(!class_exists('CreateMediaTable')){
			$this->executeArtisanProcess('vendor:publish', [
				'--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
				'--tag' => 'migrations',
			]);			
		}

		$this->line(" Creating users table (using Laravel's default migration)");
		try{
			$this->executeArtisanProcess('migrate');
		}catch(\Throwable $ex){
			$this->error(' ' . $ex->getMessage());
		}

		// $this->line(" Creating App\Http\Middleware\CheckIfAdmin.php");
		// $this->executeArtisanProcess('backpack:publish-middleware');

		$this->progressBar->finish();
		$this->info(' Admin installation finished.');
	}
}