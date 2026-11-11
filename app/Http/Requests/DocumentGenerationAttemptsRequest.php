<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentGenerationAttemptsRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'document_id' => ['required', 'exists:documents'],
			'attemp_number' => ['required', 'integer'],
			'status' => ['required'],
			'error_message' => ['required'],
			'started_at' => ['required', 'date'],
			'completed_at' => ['required', 'date'],
			'duration_ms' => ['required', 'integer'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
