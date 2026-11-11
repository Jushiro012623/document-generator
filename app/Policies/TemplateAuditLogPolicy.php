<?php

namespace App\Policies;

use App\Models\TemplateAuditLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplateAuditLogPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, TemplateAuditLog $templateAuditLog): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, TemplateAuditLog $templateAuditLog): bool
	{
	}

	public function delete(User $user, TemplateAuditLog $templateAuditLog): bool
	{
	}

	public function restore(User $user, TemplateAuditLog $templateAuditLog): bool
	{
	}

	public function forceDelete(User $user, TemplateAuditLog $templateAuditLog): bool
	{
	}
}
