<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('template_assets', function (Blueprint $table) {
			$table->id();
			$table->foreignId('template_version_id')->constrained('template_versions');
			$table->string('filename', 255);
			$table->string('storage_disk', 50)->default('s3');
			$table->string('storage_path', 500);
			$table->string('mime_type', 150);
			$table->bigInteger('file_size');
			$table->timestamps();
			$table->softDeletes();

			$table->index('template_version_id');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('template_assets');
	}
};
