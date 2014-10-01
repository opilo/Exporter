<?php namespace Opilo\Exporter; 

use Illuminate\Support\ServiceProvider;
use Passerines\Wings\FileSystem\FileManager;
use Passerines\Wings\Utility\Csvizer\Csvizer;

/**
 * Class ExporterServiceProvider
 *
 * @package Opilo\Exporter
 */
class ExporterServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
	{
        $this->package('opilo/eloquent-exporter', 'opilo/exporter');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('exporter', function($app) {
			return new ExporterManager(
				$app['config'],
				new FileManager(),
				new Csvizer($app['config']->get('opilo/exporter::csv_delimiter'))
			);
		});
	}

}
