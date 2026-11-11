<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateAuditLogRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'template_id' => ['required', 'exists:templates'],
			'template_version_id' => ['required', 'exists:template_versions'],
			'actor_type' => ['required'],
			'actor_id' => ['required', 'integer'],
			'action' => ['required'],
			'changes' => ['required'],
			'metadata' => ['required'],
			'created_at' => ['required', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
