<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditLogRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'application_id' => ['required', 'exists:applications'],
			'actor_type' => ['required'],
			'actor_id' => ['required', 'integer'],
			'action' => ['required'],
			'subject_type' => ['required'],
			'subject_id' => ['required', 'integer'],
			'metadata' => ['required'],
			'ip_address' => ['required'],
			'user_agent' => ['required'],
			'created_at' => ['required', 'date'],
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
