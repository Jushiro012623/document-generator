<?php

namespace App\Services\Jwt;

use App\Services\Jwt\Contracts\JwtDriverInterface;
use App\Services\Jwt\Contracts\RevocableJwtDriverInterface;

final readonly class JwtService
{
	public function __construct(private JwtDriverInterface $driver)
	{
	}

	public function issue(int|string $userId, int $tokenVersion, array $claims = []): string
	{
		return $this->driver->issue($userId, $tokenVersion, $claims);
	}

	public function verify(string $token): JwtPayload
	{
		return $this->driver->verify($token);
	}

	public function revoke(JwtPayload $payload): bool
	{
		if (!$this->driver instanceof RevocableJwtDriverInterface) {
			return false;
		}
		$this->driver->revoke($payload);
		return true;
	}
}
