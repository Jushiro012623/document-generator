<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('audit_logs', function (Blueprint $table) {
			$table->id();
			$table->foreignId('application_id')->constrained('applications');
			$table->string('actor_type', 50);
			$table->bigInteger('actor_id');
			$table->string('action', 150);
			$table->string('subject_type', 150);
			$table->bigInteger('subject_id');
			$table->json('metadata');
			$table->string('ip_address', 50);
			$table->text('user_agent');
			$table->timestamp('created_at');

			$table->index('application_id');
			$table->index('action');
			$table->index(['subject_type', 'subject_id']);
			$table->index(['actor_type', 'actor_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('audit_logs');
	}
};
