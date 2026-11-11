<?php

use App\Enums\TemplateStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('templates', function (Blueprint $table) {
			$table->id();
			$table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
			$table->string('name', 150);
			$table->string('slug', 150);
			$table->string('description')->nullable();
			$table->enum('status', array_column(TemplateStatus::cases(), 'value'))->default(TemplateStatus::ACTIVE->value);
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['application_id', 'slug']);
			$table->index(['application_id', 'status']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('templates');
	}
};
