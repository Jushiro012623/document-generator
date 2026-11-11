<?php

namespace App\Policies;

use App\Models\Application;

class DocumentGenerationPolicy
{
	public function authorize(Application $application, string|array $permissions): bool
	{
		$permissions = is_array($permissions)
			? $permissions
			: explode('|', $permissions);

		$permissions = array_map('trim', $permissions);

		return $application->permissions()->whereIn('permission', $permissions)->count() === count($permissions);
	}
}