<?php

use App\Enums\ApplicationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('applications', function (Blueprint $table) {
			$table->id();
			$table->string('name', 150);
			$table->string('slug', 150)->unique();
			$table->text('description')->nullable();
			$table->enum('status', array_column(ApplicationStatus::cases(), 'value'))->default(ApplicationStatus::ACTIVE->value);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('applications');
	}
};
