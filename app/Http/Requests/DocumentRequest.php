<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'application_id' => ['required', 'exists:applications'],
			'template_id' => ['required', 'exists:templates'],
			'template_version_id' => ['required', 'exists:template_versions'],
			'reference' => ['required'],
			'filename' => ['nullable'],
			'format' => ['required'],
			'status' => ['required'],
			'storage_disk' => ['required'],
			'storage_path' => ['required'],
			'mime_type' => ['required'],
			'file_size' => ['required'],
			'error_message' => ['required'],
			'requested_at' => ['required', 'date'],
			'started_at' => ['required', 'date'],
			'completed_at' => ['required', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
