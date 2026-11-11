<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentGenerationAttempt extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'document_id',
		'attempt_number',
		'status',
		'error_message',
		'started_at',
		'completed_at',
		'duration_ms',
	];

	public function document(): BelongsTo
	{
		return $this->belongsTo(Document::class);
	}

	protected function casts(): array
	{
		return [
			'started_at' => 'timestamp',
			'completed_at' => 'timestamp',
			'status' => DocumentStatus::class
		];
	}
}
