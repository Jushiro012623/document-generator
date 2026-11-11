<?php

namespace App\Traits;

trait UpdateRequestTrait
{
	private function isUpdating(): bool
	{
		return $this->isMethod('PUT') || $this->isMethod('PATCH');
	}

	private function isRequired(): string
	{
		return $this->isUpdating() ? "nullable" : "required";
	}

}
