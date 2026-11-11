<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentIdempotencyKey extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'application_id',
		'idempotency_key',
		'request_hash',
		'document_id',
		'expires_at',
	];

	public function application(): BelongsTo
	{
		return $this->belongsTo(Application::class);
	}

	public function document(): BelongsTo
	{
		return $this->belongsTo(Document::class);
	}

	protected function casts(): array
	{
		return [
			'expires_at' => 'timestamp',
		];
	}
}
