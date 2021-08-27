<?php

namespace Dotlogics\Admin\App\Console\Commands;

use Illuminate\Console\Command;

class PublishView extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'laravel-admin:publish';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'laravel-admin:publish
							{subpath : short path to the view file (ex: fields/text)}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publishes a Laravel Admin view so you can make changes to it, for your project.';

	/**
	 * The directory where the views will be published FROM.
	 *
	 * @var string
	 */
	public $sourcePath = 'vendor/jd-dotlogics/laravel-admin/src/resources/views/';

	/**
	 * The directory where the views will pe published TO.
	 *
	 * @var string
	 */
	public $destinationPath = 'resources/views/vendor/laravel-admin/';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->file = strtolower($this->argument('subpath'));

		return $this->publishFile($this->file);
	}

	/**
	 * Take a blade file from the vendor folder and publish it to the resources folder.
	 *
	 * @param string $file The filename without extension
	 *
	 * @return void
	 */
	protected function publishFile($file)
	{
		$sourceFile = $this->sourcePath.$file.'.blade.php';
		$copiedFile = $this->destinationPath.$file.'.blade.php';

		if (! file_exists($sourceFile)) {
			return $this->error(
				'Cannot find source view file at '
				.$sourceFile.
				' - make sure you\'ve picked an existing view file'
			);
		} else {
			$canCopy = true;

			if (file_exists($copiedFile)) {
				$canCopy = $this->confirm(
					'File already exists at '
					.$copiedFile.
					' - do you want to overwrite it?'
				);
			}

			if ($canCopy) {
				$path = pathinfo($copiedFile);

				if (! file_exists($path['dirname'])) {
					mkdir($path['dirname'], 0755, true);
				}

				if (copy($sourceFile, $copiedFile)) {
					$this->info('Copied to '.$copiedFile);
				} else {
					return $this->error(
						'Failed to copy '
						.$sourceFile.
						' to '
						.$copiedFile.
						' for unknown reason'
					);
				}
			}
		}
	}
}