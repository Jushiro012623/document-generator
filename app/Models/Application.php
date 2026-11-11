<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name',
		'slug',
		'description',
		'status',
		'template_id',
	];

	protected function casts(): array
	{
		return [
			'status' => ApplicationStatus::class
		];
	}
	public function credentials(): HasMany
	{
		return $this->hasMany(ApplicationCredential::class);
	}

	public function templates(): HasMany
	{
		return $this->hasMany(Template::class);
	}

	public function permissions(): HasMany
	{
		return $this->hasMany(ApplicationPermission::class);
	}

	public function document(): HasMany
	{
		return $this->hasMany(Document::class);
	}

	public function documentIdempotencyKeys(): HasMany
	{
		return $this->hasMany(DocumentIdempotencyKey::class);
	}

	public function auditLogs(): HasMany
	{
		return $this->hasMany(AuditLog::class);
	}


}
