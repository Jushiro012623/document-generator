<?php

namespace App\Services\Jwt\Contracts;

use App\Services\Jwt\JwtPayload;

interface RevocableJwtDriverInterface
{
	public function revoke(JwtPayload $payload): void;
}