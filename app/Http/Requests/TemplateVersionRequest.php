<?php

namespace App\Http\Requests;

use App\Enums\TemplateFormat;
use App\Traits\UpdateRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateVersionRequest extends FormRequest
{
	use UpdateRequestTrait;
	public function rules(): array
	{
		return [
			'file' => ['nullable'],
			'format' => [
				$this->isRequired(),
				'string',
				'distinct',
				Rule::in(array_column(TemplateFormat::cases(), 'value')),
			],
			'content' => ['nullable', 'string'],
			'styles' => ['nullable', 'string'],
			'storage_path' => ['nullable', 'string'],
			'data_schema' => ['nullable', 'array'],
			'sample_data' => ['nullable', 'array'],
			'page_settings' => ['nullable', 'array'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
