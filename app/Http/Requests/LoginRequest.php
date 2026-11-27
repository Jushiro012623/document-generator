<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'identifier' => ['string', 'required'],
			'password' => ['string', 'required']
		];
	}

	public function authorize(): bool
	{
		return true;
	}
}
