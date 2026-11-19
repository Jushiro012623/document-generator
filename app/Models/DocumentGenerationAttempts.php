<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentGenerationAttempts extends Model
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

	protected $casts = [
		'status' => DocumentStatus::class,
		'started_at' => 'datetime',
		'completed_at' => 'datetime',
		'attempt_number' => 'integer',
		'duration_ms' => 'integer',
	];

	/**
	 * Get the document that owns the generation attempt.
	 */
	public function document(): BelongsTo
	{
		return $this->belongsTo(Document::class);
	}
}
