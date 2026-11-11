<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentIdempotencyKeys;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DocumentIdempotencyKeysFactory extends Factory
{
	protected $model = DocumentIdempotencyKeys::class;

	public function definition(): array
	{
		return [
			'idempotency_key' => $this->faker->word(),
			'request_hash' => $this->faker->word(),
			'expires_at' => Carbon::now(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'application_id' => Application::factory(),
			'document_id' => Document::factory(),
		];
	}
}
