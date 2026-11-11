<?php

namespace App\Http\Requests;

use App\Traits\UpdateRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
{
	use UpdateRequestTrait;

	public function rules(): array
	{

		return [
			'name' => [$this->isRequired(), 'string'],
			'slug' => [$this->isRequired(), 'string', Rule::unique('templates', 'slug')
				->where('application_id', $this->input('application_id')),],
			'description' => ['nullable', 'string'],
			'status' => ['nullable', 'string'],
			'application_id' => ['sometimes', 'exists:applications,id', 'integer']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
