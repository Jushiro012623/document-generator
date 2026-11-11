<?php

namespace App\Policies;

use App\Models\ApplicationPermission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPermissionPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, ApplicationPermission $applicationPermission): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, ApplicationPermission $applicationPermission): bool
	{
	}

	public function delete(User $user, ApplicationPermission $applicationPermission): bool
	{
	}

	public function restore(User $user, ApplicationPermission $applicationPermission): bool
	{
	}

	public function forceDelete(User $user, ApplicationPermission $applicationPermission): bool
	{
	}
}
