<?php

namespace App\Http\Requests;

use App\Context\AppContext;
use App\Traits\UpdateRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
{
	use UpdateRequestTrait;

	public function rules(): array
	{
		$applicationId = $this->route('application')?->id ?? app(AppContext::class)->app->id;

		$templateId = $this->route('template');

		return [
			'name' => [$this->isRequired(), 'string'],
			'slug' => [
				$this->isRequired(),
				'string',
				Rule::unique('templates', 'slug')
					->where('application_id', $applicationId)
					->ignore($templateId),
			],
			'description' => ['nullable', 'string'],
			'status' => ['nullable', 'string'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
