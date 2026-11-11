<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TemplateFactory extends Factory
{
	protected $model = Template::class;

	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'slug' => $this->faker->slug(),
			'description' => $this->faker->text(),
			'status' => $this->faker->word(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'application_id' => Application::factory(),
		];
	}
}
