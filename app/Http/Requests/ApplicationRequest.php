<?php

namespace App\Http\Requests;

use App\Enums\ApplicationPermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'max:255'],
			'slug' => ['required', 'string', 'max:255', 'unique:applications,slug'],
			'description' => ['required', 'string'],

			'permissions' => ['sometimes', 'array'],
			'permissions.*' => ['required', 'array'],
			'permissions.*.permission' => [
				'required',
				'string',
				'distinct',
				Rule::in(array_column(ApplicationPermission::cases(), 'value')),
			],
			'permissions.*.description' => ['nullable', 'string'],

			'credentials' => ['sometimes', 'array'],
			'credentials.*' => ['required', 'array'],
			'credentials.*.name' => ['required', 'string'],
			'credentials.*.expires_at' => ['nullable', 'date'],

		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
