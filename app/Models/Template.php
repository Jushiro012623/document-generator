<?php

namespace App\Models;

use App\Enums\TemplateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'application_id',
		'name',
		'slug',
		'description',
		'status',
	];

	protected function casts(): array
	{
		return [
			'status' => TemplateStatus::class
		];
	}

	public function application(): BelongsTo
	{
		return $this->belongsTo(Application::class);
	}

	public function versions(): HasMany
	{
		return $this->hasMany(TemplateVersion::class);
	}

	public function templateAuditLogs(): HasMany
	{
		return $this->hasMany(TemplateAuditLog::class);
	}

	public function documents(): HasMany
	{
		return $this->hasMany(Document::class);
	}
}
