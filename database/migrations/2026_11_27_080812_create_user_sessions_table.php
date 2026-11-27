<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('user_sessions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id');
			$table->string('refresh_token_hash')->unique();
			$table->string('device_name')->nullable();
			$table->string('ip_address')->nullable();
			$table->timestamp('last_used_at')->nullable();
			$table->timestamp('expires_at');
			$table->timestamp('revoked_at')->nullable();
			$table->timestamps();

			$table->index(['user_id', 'revoked_at']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('user_sessions');
	}
};
