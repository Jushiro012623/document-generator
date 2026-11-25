<?php

namespace App\Services\Jwt\Drivers\Lcobucci\Constraints;

use DateInterval;
use DateTimeZone;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

class ValidationConstraints
{
	/**
	 * @throws \DateInvalidTimeZoneException
	 * @throws \DateMalformedIntervalStringException
	 */
	public static function make(Configuration $config, Token $token): array
	{
		$clock = new SystemClock(new DateTimeZone(config('app.timezone')));
		$leeway = new DateInterval('PT' . config('jwt.leeway') . 'S');

		return [
			new TokenNotRevoked(),
			new IssuedBy(config('app.url')),
			new PermittedFor(config('app.url')),
			new SignedWith($config->signer(), $config->signingKey()),
			new StrictValidAt($clock, $leeway)
		];
	}
}
