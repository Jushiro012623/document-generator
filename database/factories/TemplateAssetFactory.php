<?php

namespace Database\Factories;

use App\Models\TemplateAsset;
use App\Models\TemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TemplateAssetFactory extends Factory
{
	protected $model = TemplateAsset::class;

	public function definition(): array
	{
		return [
			'filename' => $this->faker->word(),
			'storage_disk' => $this->faker->word(),
			'storage_path' => $this->faker->word(),
			'mime_type' => $this->faker->word(),
			'file_size' => $this->faker->randomNumber(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'template_version_id' => TemplateVersion::factory(),
		];
	}
}
