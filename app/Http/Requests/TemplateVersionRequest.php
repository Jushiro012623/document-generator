<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateVersionRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'template_id' => ['required', 'exists:templates'],
			'version' => ['required', 'integer'],
			'format' => ['required'],
			'status' => ['required'],
			'content' => ['required'],
			'styles' => ['required'],
			'storage_path' => ['required'],
			'data_schema' => ['required'],
			'sample_data' => ['required'],
			'page_settings' => ['required'],
			'published_at' => ['required', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
