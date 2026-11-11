<?php

namespace App\Models;

use App\Enums\TemplateFormat;
use App\Enums\TemplateVersionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateVersion extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'template_id',
		'version',
		'format',
		'status',
		'content',
		'styles',
		'storage_path',
		'data_schema',
		'sample_data',
		'page_settings',
		'published_at',
	];

	public function template(): BelongsTo
	{
		return $this->belongsTo(Template::class);
	}

	public function templateAssets(): HasMany
	{
		return $this->hasMany(TemplateAsset::class);
	}

	public function templateAuditLogs(): HasMany
	{
		return $this->hasMany(TemplateAuditLog::class);
	}

	public function documents(): HasMany
	{
		return $this->hasMany(Document::class);
	}
	protected function casts(): array
	{
		return [
			'data_schema' => 'array',
			'sample_data' => 'array',
			'page_settings' => 'array',
			'published_at' => 'timestamp',
			'status' => TemplateVersionStatus::class,
			'format' => TemplateFormat::class,
		];
	}


}
