<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationPermissionRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'application_id' => ['required', 'exists:applications'],
			'permission' => ['required'],
			'description' => ['required'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
