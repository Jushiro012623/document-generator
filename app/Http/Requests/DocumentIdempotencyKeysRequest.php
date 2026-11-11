<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentIdempotencyKeysRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'application_id' => ['required', 'exists:applications'],
			'idempotency_key' => ['required'],
			'request_hash' => ['required'],
			'document_id' => ['required', 'exists:documents'],
			'expires_at' => ['required', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
