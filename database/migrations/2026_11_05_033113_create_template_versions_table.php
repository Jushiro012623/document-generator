<?php

use App\Enums\TemplateFormat;
use App\Enums\TemplateVersionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('template_versions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
			$table->integer('version');
			$table->enum('format', array_column(TemplateFormat::cases(), 'value'));
			$table->enum('status', array_column(TemplateVersionStatus::cases(), 'value'))->default(TemplateVersionStatus::DRAFT->value);
			$table->longText('content')->nullable();
			$table->longText('styles')->nullable();
			$table->string('storage_path', 500)->nullable();
			$table->json('data_schema')->nullable();
			$table->json('sample_data')->nullable();
			$table->json('page_settings')->nullable();
			$table->timestamp('published_at')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['template_id', 'version']);
			$table->index(['template_id', 'status']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('template_versions');
	}
};
