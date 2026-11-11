<?php

namespace App\Policies;

use App\Models\TemplateVersion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplateVersionPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, TemplateVersion $templateVersion): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, TemplateVersion $templateVersion): bool
	{
	}

	public function delete(User $user, TemplateVersion $templateVersion): bool
	{
	}

	public function restore(User $user, TemplateVersion $templateVersion): bool
	{
	}

	public function forceDelete(User $user, TemplateVersion $templateVersion): bool
	{
	}
}
