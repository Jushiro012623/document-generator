<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\TemplateAuditLog;
use App\Models\TemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TemplateAuditLogFactory extends Factory
{
	protected $model = TemplateAuditLog::class;

	public function definition(): array
	{
		return [
			'actor_type' => $this->faker->word(),
			'actor_id' => $this->faker->randomNumber(),
			'action' => $this->faker->word(),
			'changes' => $this->faker->words(),
			'metadata' => $this->faker->words(),
			'created_at' => Carbon::now(),

			'template_id' => Template::factory(),
			'template_version_id' => TemplateVersion::factory(),
		];
	}
}
