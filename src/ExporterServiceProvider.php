<?php namespace Opilo\Exporter; 

use Illuminate\Support\ServiceProvider;

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
		// do nothing
	}

}
