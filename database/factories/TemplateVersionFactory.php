<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\TemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TemplateVersionFactory extends Factory
{
	protected $model = TemplateVersion::class;

	public function definition(): array
	{
		return [
			'version' => $this->faker->randomNumber(),
			'format' => $this->faker->word(),
			'status' => $this->faker->word(),
			'content' => $this->faker->word(),
			'styles' => $this->faker->word(),
			'storage_path' => $this->faker->word(),
			'data_schema' => $this->faker->words(),
			'sample_data' => $this->faker->words(),
			'page_settings' => $this->faker->words(),
			'published_at' => Carbon::now(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'template_id' => Template::factory(),
		];
	}
}
