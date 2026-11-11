<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateAssetRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'template_version_id' => ['required', 'exists:template_versions'],
			'filename' => ['required'],
			'storage_disk' => ['required'],
			'storage_path' => ['required'],
			'mime_type' => ['required'],
			'file_size' => ['required', 'integer'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
