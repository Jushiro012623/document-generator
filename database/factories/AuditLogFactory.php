<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AuditLogFactory extends Factory
{
	protected $model = AuditLog::class;

	public function definition(): array
	{
		return [
			'actor_type' => $this->faker->word(),
			'actor_id' => $this->faker->randomNumber(),
			'action' => $this->faker->word(),
			'subject_type' => $this->faker->word(),
			'subject_id' => $this->faker->randomNumber(),
			'metadata' => $this->faker->words(),
			'ip_address' => $this->faker->ipv4(),
			'user_agent' => $this->faker->word(),
			'created_at' => Carbon::now(),

			'application_id' => Application::factory(),
		];
	}
}
