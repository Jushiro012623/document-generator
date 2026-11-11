<?php

namespace App\Policies;

use App\Models\ApplicationCredential;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationCredentialsPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, ApplicationCredential $applicationCredentials): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, ApplicationCredential $applicationCredentials): bool
	{
	}

	public function delete(User $user, ApplicationCredential $applicationCredentials): bool
	{
	}

	public function restore(User $user, ApplicationCredential $applicationCredentials): bool
	{
	}

	public function forceDelete(User $user, ApplicationCredential $applicationCredentials): bool
	{
	}
}
