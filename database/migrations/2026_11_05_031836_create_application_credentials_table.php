<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('application_credentials', function (Blueprint $table) {
			$table->id();
			$table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
			$table->string('name', 150);
			$table->string('key_prefix', 30)->unique();
			$table->string('secret_hash', 255);
			$table->timestamp('last_used_at')->nullable();
			$table->timestamp('expires_at')->nullable();
			$table->timestamp('revoked_at')->nullable();
			$table->timestamps();
			$table->softDeletes();

		});
	}

	public function down(): void
	{
		Schema::dropIfExists('application_credentials');
	}
};
