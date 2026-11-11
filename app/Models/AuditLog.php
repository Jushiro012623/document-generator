<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'application_id',
		'actor_type',
		'actor_id',
		'action',
		'subject_type',
		'subject_id',
		'metadata',
		'ip_address',
		'user_agent',
		'created_at',
	];

	public function application(): BelongsTo
	{
		return $this->belongsTo(Application::class);
	}

	protected function casts(): array
	{
		return [
			'metadata' => 'array',
			'created_at' => 'timestamp',
		];
	}
}
