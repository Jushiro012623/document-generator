<?php

namespace App\Services\Jwt\Drivers\Lcobucci\Constraints;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;

class TokenNotRevoked implements Constraint
{
	public function assert(Token $token): void
	{
		if (!$jti = $token->claims()->get('jti')) {
			throw new ConstraintViolation('Token is missing required jti claim');
		}

		if (cache()->has("blacklisted_tokens:{$jti}")) {
			throw new ConstraintViolation('Token is blacklisted');
		}
	}
}
