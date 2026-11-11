<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\User;
use App\Policies\DocumentGenerationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	public function register(): void
	{

	}

	public function boot(): void
	{
		Gate::define('document-generator', function (?User $user, Application $application, string|array $permissions) {
			return app(DocumentGenerationPolicy::class)->authorize($application, $permissions);
		});
	}
}
