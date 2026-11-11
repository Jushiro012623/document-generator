<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationCredential;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApplicationCredentialsFactory extends Factory
{
	protected $model = ApplicationCredential::class;

	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'key_prefix' => $this->faker->word(),
			'secret_hash' => $this->faker->word(),
			'last_used_at' => Carbon::now(),
			'expires_at' => Carbon::now(),
			'revoked_at' => Carbon::now(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'application_id' => Application::factory(),
		];
	}
}
