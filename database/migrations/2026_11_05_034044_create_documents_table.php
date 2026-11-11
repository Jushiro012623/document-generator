<?php

use App\Enums\DocumentFormat;
use App\Enums\DocumentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('documents', function (Blueprint $table) {
			$table->id();
			$table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
			$table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
			$table->foreignId('template_version_id')->constrained('template_versions')->cascadeOnDelete();
			$table->string('reference', 100);
			$table->string('filename')->nullable();
			$table->json('generation_data')->nullable();
			$table->enum('format', array_column(DocumentFormat::cases(), 'value'));
			$table->enum('status', array_column(DocumentStatus::cases(), 'value'))->default(DocumentStatus::PENDING->value);
			$table->string('storage_disk', 50)->nullable();
			$table->string('storage_path', 500)->nullable();
			$table->string('mime_type', 150)->nullable();
			$table->bigInteger('file_size')->nullable();
			$table->text('error_message')->nullable();
			$table->timestamp('requested_at')->nullable();
			$table->timestamp('started_at')->nullable();
			$table->timestamp('completed_at')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->index(['application_id', 'status']);
			$table->index(['application_id', 'template_id']);
			$table->index('template_version_id');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('documents');
	}
};
