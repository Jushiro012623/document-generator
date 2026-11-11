<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationCredentialsRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'expires_at' => ['nullable', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
