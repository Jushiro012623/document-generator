<?php

namespace App\Http\Requests;

use App\Enums\ApplicationPermission;
use App\Traits\UpdateRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationPermissionRequest extends FormRequest
{
	use UpdateRequestTrait;
	public function rules(): array
	{
		return [
			'permission' => [
				$this->isRequired(),
				'string',
				'distinct',
				Rule::in(array_column(ApplicationPermission::cases(), 'value')),
			],
			'description' => ['nullable', 'string'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
