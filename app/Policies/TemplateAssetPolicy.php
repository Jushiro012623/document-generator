<?php

namespace App\Policies;

use App\Models\TemplateAsset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplateAssetPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, TemplateAsset $templateAsset): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, TemplateAsset $templateAsset): bool
	{
	}

	public function delete(User $user, TemplateAsset $templateAsset): bool
	{
	}

	public function restore(User $user, TemplateAsset $templateAsset): bool
	{
	}

	public function forceDelete(User $user, TemplateAsset $templateAsset): bool
	{
	}
}
