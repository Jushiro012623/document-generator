<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('template_audit_logs', function (Blueprint $table) {
			$table->id();
			$table->foreignId('template_id')->constrained('templates');
			$table->foreignId('template_version_id')->constrained('template_versions');
			$table->string('actor_type', 50);
			$table->bigInteger('actor_id')->nullable();
			$table->string('action', 100);
			$table->json('changes')->nullable();
			$table->json('metadata')->nullable();
			$table->timestamp('created_at');

			$table->index('template_id');
			$table->index('template_version_id');
			$table->index(['actor_type', 'actor_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('template_audit_logs');
	}
};
