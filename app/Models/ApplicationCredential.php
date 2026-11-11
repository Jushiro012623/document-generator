<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationCredential extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'application_id',
		'name',
		'key_prefix',
		'secret_hash',
		'last_used_at',
		'expires_at',
		'revoked_at',
	];

	public function application(): BelongsTo
	{
		return $this->belongsTo(Application::class);
	}

	protected function casts(): array
	{
		return [
			'last_used_at' => 'timestamp',
			'expires_at' => 'timestamp',
			'revoked_at' => 'timestamp',
		];
	}


}
