<?php

namespace App\Providers;

use App\Context\AppContext;
use App\Models\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		$this->app->scoped(AppContext::class, function ($app) {
			$application = $app['request']->attributes->get('application');

			if (!$application instanceof Application) {
				throw new AuthenticationException('Application authentication required.');
			}

			return new AppContext($application);
		});
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		//
	}
}
