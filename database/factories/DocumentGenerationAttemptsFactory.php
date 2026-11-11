<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentGenerationAttempts;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DocumentGenerationAttemptsFactory extends Factory
{
	protected $model = DocumentGenerationAttempts::class;

	public function definition(): array
	{
		return [
			'attemp_number' => $this->faker->randomNumber(),
			'status' => $this->faker->word(),
			'error_message' => $this->faker->word(),
			'started_at' => Carbon::now(),
			'completed_at' => Carbon::now(),
			'duration_ms' => $this->faker->randomNumber(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'document_id' => Document::factory(),
		];
	}
}
