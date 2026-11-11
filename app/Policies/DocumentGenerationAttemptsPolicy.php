<?php

namespace App\Policies;

use App\Models\DocumentGenerationAttempts;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentGenerationAttemptsPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, DocumentGenerationAttempts $documentGenerationAttempts): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, DocumentGenerationAttempts $documentGenerationAttempts): bool
	{
	}

	public function delete(User $user, DocumentGenerationAttempts $documentGenerationAttempts): bool
	{
	}

	public function restore(User $user, DocumentGenerationAttempts $documentGenerationAttempts): bool
	{
	}

	public function forceDelete(User $user, DocumentGenerationAttempts $documentGenerationAttempts): bool
	{
	}
}
