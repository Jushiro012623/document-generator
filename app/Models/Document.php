<?php

namespace App\Models;

use App\Enums\DocumentFormat;
use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'application_id',
		'template_id',
		'template_version_id',
		'reference',
		'generation_data',
		'filename',
		'format',
		'status',
		'storage_disk',
		'storage_path',
		'mime_type',
		'file_size',
		'error_message',
		'requested_at',
		'started_at',
		'completed_at',
	];

	public function application(): BelongsTo
	{
		return $this->belongsTo(Application::class);
	}

	public function template(): BelongsTo
	{
		return $this->belongsTo(Template::class);
	}

	public function templateVersion(): BelongsTo
	{
		return $this->belongsTo(TemplateVersion::class);
	}

	public function documentGenerationAttempts(): HasMany
	{
		return $this->hasMany(DocumentGenerationAttempts::class);
	}

	public function documentIdempotencyKeys(): HasMany
	{
		return $this->hasMany(DocumentIdempotencyKey::class);
	}

	protected function casts(): array
	{
		return [
			'generation_data' => 'array',
			'requested_at' => 'timestamp',
			'started_at' => 'timestamp',
			'completed_at' => 'timestamp',
			'format' => DocumentFormat::class,
			'status' => DocumentStatus::class
		];
	}
}
