<?php

namespace App\Http\Requests;

use App\Enums\ApplicationPermission;
use App\Traits\UpdateRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationCredentialsRequest extends FormRequest
{
	use UpdateRequestTrait;

	public function rules(): array
	{
		return [
			'name' => [$this->isRequired(), 'string'],
			'expires_at' => ['nullable', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
