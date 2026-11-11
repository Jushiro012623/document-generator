<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Document;
use App\Models\Template;
use App\Models\TemplateVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DocumentFactory extends Factory
{
	protected $model = Document::class;

	public function definition(): array
	{
		return [
			'reference' => $this->faker->word(),
			'filename' => $this->faker->word(),
			'format' => $this->faker->word(),
			'status' => $this->faker->word(),
			'storage_disk' => $this->faker->word(),
			'storage_path' => $this->faker->word(),
			'mime_type' => $this->faker->word(),
			'file_size' => $this->faker->word(),
			'error_message' => $this->faker->word(),
			'requested_at' => Carbon::now(),
			'started_at' => Carbon::now(),
			'completed_at' => Carbon::now(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),

			'application_id' => Application::factory(),
			'template_id' => Template::factory(),
			'template_version_id' => TemplateVersion::factory(),
		];
	}
}
