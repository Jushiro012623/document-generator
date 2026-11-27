<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
	protected $fillable = [
		'user_id',
		'refresh_token_hash',
		'device_name',
		'ip_address',
		'last_used_at',
		'expires_at',
		'revoked_at',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
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
