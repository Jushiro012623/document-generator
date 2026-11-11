<?php

namespace App\Http\Requests;

use App\Enums\DocumentFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentGenerationRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'template_id' => ['required', 'integer',],
			'template_version_id' => ['nullable', 'integer',],
			'format' => ['required', Rule::enum(DocumentFormat::class),],
			'mode' => ['sometimes', Rule::in(['sync', 'async']),],
			'data' => ['required', 'array',],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
