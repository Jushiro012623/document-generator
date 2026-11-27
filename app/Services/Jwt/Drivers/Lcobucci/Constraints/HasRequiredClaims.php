<?php

namespace App\Services\Jwt\Drivers\Lcobucci\Constraints;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\ConstraintViolation;

final readonly class HasRequiredClaims implements Constraint
{
	public function __construct(
		private array $claims,
	)
	{
	}

	public function assert(Token $token): void
	{
		foreach ($this->claims as $claim) {
			if (!$token->claims()->has($claim)) {
				throw new ConstraintViolation(sprintf('The token is missing the required claim "%s".', $claim));
			}

			$value = $token->claims()->get($claim);

			if ($value === null || $value === '') {
				throw new ConstraintViolation(sprintf('Invalid value for required claim "%s".', $claim));
			}

			if (in_array($claim, ['sub', 'jti', 'iss'], true) && !is_string($value)) {
				throw new ConstraintViolation(sprintf('Claim "%s" must be a string.', $claim));
			}

			if (in_array($claim, ['iat', 'nbf', 'exp'], true) && !$value instanceof \DateTimeImmutable) {
				throw new ConstraintViolation(sprintf('Claim "%s" must be a valid date.', $claim));
			}

			if ($claim === 'ver' && (!is_int($value) || $value < 1)) {
				throw new ConstraintViolation('Claim "ver" must be a positive integer.');
			}
		}
	}
}
