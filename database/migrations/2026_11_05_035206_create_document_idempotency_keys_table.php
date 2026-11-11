<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('document_idempotency_keys', function (Blueprint $table) {
			$table->id();
			$table->foreignId('application_id')->constrained('applications');
			$table->string('idempotency_key');
			$table->string('request_hash', 64);
			$table->foreignId('document_id')->constrained('documents');
			$table->timestamp('expires_at');
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['application_id','idempotency_key']);
			$table->index('document_id');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('document_idempotency_keys');
	}
};
