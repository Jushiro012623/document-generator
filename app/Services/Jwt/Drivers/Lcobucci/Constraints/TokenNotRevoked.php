<?php

namespace App\Services\Jwt\Drivers\Lcobucci\Constraints;

use Illuminate\Support\Facades\Cache;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;

class TokenNotRevoked implements Constraint
{
	public function assert(Token $token): void
	{
		if (!config('jwt.blacklist_enabled')) {
			return;
		}

		$jti = $token->claims()->get('jti');

		if (Cache::has("blacklisted_tokens:{$jti}")) {
			throw new ConstraintViolation('Token is blacklisted');
		}
	}
}
