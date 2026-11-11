<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('application_permissions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
			$table->string('permission', 150);
			$table->text('description')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['application_id', 'permission']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('application_permissions');
	}
};
