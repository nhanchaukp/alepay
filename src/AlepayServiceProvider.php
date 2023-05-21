<?php

namespace Nhanchaukp\Alepay;

use Illuminate\Support\ServiceProvider;
use File;
use Nhanchaukp\Alepay\Facades\Alepay;

 class AlepayServiceProvider extends ServiceProvider
{

	public function boot()
	{
		$this->registerModule();
	}

	/**
	 * Register bindings in the container.
	 */
	public function register()
	{
		$this->configure();
		$this->offerPublishing();
	}

	/**
	 * Setup the configuration.
	 */
	private function configure(): void
	{
		$this->mergeConfigFrom(__DIR__ . '/config/alepay.php', 'alepay');
	}

	/**
	 * Setup the resource publishing groups.
	 */
	private function offerPublishing(): void
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/config/alepay.php' => config_path('alepay.php'),
			], 'alepay-config');

			$this->publishes([
				__DIR__ . '/resources/views' => resource_path('views/vendor/alepay'),
			], 'alepay-views');
		}
	}

	private function registerModule()
	{
		$moduleName = 'Alepay';

		// boot route
		if (File::exists(__DIR__ . '/routes/routes.php')) {
			$this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
		}

		// boot views
		if (File::exists(__DIR__ . '/resources/views')) {
			$this->loadViewsFrom(__DIR__ . '/resources/views', $moduleName);
		}
	}
}
