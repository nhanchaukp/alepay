<?php

namespace Nhanchaukp\Alepay;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use File;

 class AlepayServiceProvider extends ServiceProvider implements DeferrableProvider
{

	/**
	 * Register commands file here
	 * alias => path
	 */
	protected $commands = [

	];

	/**
	 * Register bindings in the container.
	 */
	public function register()
	{
		// Đăng ký config cho từng Module
		$this->configure();
		$this->offerPublishing();
		$this->registerCommands();
		// boot commands
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
		if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/config/alepay.php' => config_path('alepay.php'),
			], 'alepay-config');

			$this->publishes([
				__DIR__ . '/resources/views' => resource_path('views/vendor/alepay'),
			], 'alepay-views');
		}
	}

	/**
	 * Register the Artisan commands.
	 */
	private function registerCommands(): void
	{
		if ($this->app->runningInConsole()) {
			$this->commands($this->commands);
		}
	}

	public function boot()
	{
		$this->registerModule();
	}

	private function registerModule()
	{
		$modulePath = __DIR__ . '/';
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
