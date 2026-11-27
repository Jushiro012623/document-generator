<?php

namespace App\Services\Jwt\Contracts;

use App\Services\Jwt\JwtPayload;

interface JwtDriverInterface
{
	public function issue(string|int $subject, int $tokenVersion, array $claims = []): string;

	public function verify(string $token): JwtPayload;
}