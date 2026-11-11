<?php

namespace App\Policies;

use App\Models\DocumentIdempotencyKeys;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentIdempotencyKeysPolicy
{
	use HandlesAuthorization;

	public function viewAny(User $user): bool
	{

	}

	public function view(User $user, DocumentIdempotencyKeys $documentIdempotencyKeys): bool
	{
	}

	public function create(User $user): bool
	{
	}

	public function update(User $user, DocumentIdempotencyKeys $documentIdempotencyKeys): bool
	{
	}

	public function delete(User $user, DocumentIdempotencyKeys $documentIdempotencyKeys): bool
	{
	}

	public function restore(User $user, DocumentIdempotencyKeys $documentIdempotencyKeys): bool
	{
	}

	public function forceDelete(User $user, DocumentIdempotencyKeys $documentIdempotencyKeys): bool
	{
	}
}
