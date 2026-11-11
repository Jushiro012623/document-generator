<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationPermission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApplicationPermissionFactory extends Factory
{
	protected $model = ApplicationPermission::class;

	public function definition(): array
	{
		return [
			'permission' => $this->faker->word(),
			'description' => $this->faker->text(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'application_id' => Application::factory(),
		];
	}
}
