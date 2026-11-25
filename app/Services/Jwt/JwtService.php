<?php

namespace App\Services\Jwt;

use App\Services\Jwt\Contracts\JwtDriverInterface;

final readonly class JwtService
{
	public function __construct(private JwtDriverInterface $driver)
	{
	}

	public function issue(int|string $userId, array $claims = []): string
	{
		return $this->driver->issue($userId, array_merge(['type' => 'access'], $claims));
	}

	public function verify(string $token): JwtPayload
	{
		return $this->driver->verify($token);
	}
}
