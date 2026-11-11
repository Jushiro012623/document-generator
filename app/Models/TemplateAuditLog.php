<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateAuditLog extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'template_id',
		'template_version_id',
		'actor_type',
		'actor_id',
		'action',
		'changes',
		'metadata',
		'created_at',
	];

	public function template(): BelongsTo
	{
		return $this->belongsTo(Template::class);
	}

	public function templateVersion(): BelongsTo
	{
		return $this->belongsTo(TemplateVersion::class);
	}

	protected function casts(): array
	{
		return [
			'changes' => 'array',
			'metadata' => 'array',
			'created_at' => 'timestamp',
		];
	}
}
