<?php

use App\Enums\DocumentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('document_generation_attempts', function (Blueprint $table) {
			$table->id();
			$table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
			$table->integer('attempt_number');
			$table->enum('status', array_column(DocumentStatus::cases(), 'value'))->default(DocumentStatus::PENDING->value);
			$table->text('error_message');
			$table->timestamp('started_at');
			$table->timestamp('completed_at');
			$table->bigInteger('duration_ms');
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['document_id', 'attempt_number']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('document_generation_attempts');
	}
};
