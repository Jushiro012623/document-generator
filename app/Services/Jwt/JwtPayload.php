<?php

namespace App\Services\Jwt;

use Carbon\CarbonImmutable;

final readonly class JwtPayload
{
	public function __construct(
		public string $subject,
		public array $claims = [],
		public ?string $tokenId = null,
		public ?CarbonImmutable $expiresAt = null,
	)
	{
	}

	public function get(string $key, mixed $default = null): mixed
	{
		return $this->claims[$key] ?? $default;
	}

	public function has(string $key): bool
	{
		return array_key_exists($key, $this->claims);
	}
}