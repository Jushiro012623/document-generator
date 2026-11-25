<?php

namespace App\Services\Jwt\Drivers\Lcobucci;

use App\Services\Jwt\Contracts\JwtDriverInterface;
use App\Services\Jwt\Drivers\Lcobucci\Constraints\ValidationConstraints;
use App\Services\Jwt\JwtPayload;
use Carbon\CarbonImmutable;
use DateInvalidTimeZoneException;
use DateMalformedIntervalStringException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

final readonly class LcobucciService implements JwtDriverInterface
{
	public function __construct(private Configuration $configuration)
	{
	}

	protected function config(): Configuration
	{
		return $this->configuration;
	}

	public function issue(string $subject, array $claims = []): string
	{
		$token = $this->create($subject, $claims);

		return $token->toString();
	}

	/**
	 * @throws DateInvalidTimeZoneException
	 * @throws AuthenticationException
	 * @throws DateMalformedIntervalStringException
	 */
	public function verify(string $token): JwtPayload
	{
		$parsedToken = $this->parse($token);

		$validators = ValidationConstraints::make($this->config(), $parsedToken);

		try {
			$this->config()->validator()->assert($parsedToken, ...$validators);
		} catch (RequiredConstraintsViolated $e) {
			throw new AuthenticationException('The authentication token is invalid or has expired.');
		}

		return new JwtPayload(
			subject: $parsedToken->claims()->get('sub'),
			claims: $parsedToken->claims()->all(),
			tokenId: $parsedToken->claims()->get('jti'),
			expiresAt: $parsedToken->claims()->get('exp'),
		);
	}

	/**
	 * @throws AuthenticationException
	 */
	protected function parse(?string $tokenString): UnencryptedToken
	{
		$tokenString = is_string($tokenString) ? trim($tokenString) : $tokenString;

		if (empty($tokenString)) {
			throw new AuthenticationException('Authentication token is missing.');
		}
		try {
			return $this->config()->parser()->parse($tokenString);
		} catch (\Throwable $e) {
			throw new AuthenticationException('The authentication token is malformed.');
		}
	}

	protected function create(int|string $userPublicID, array $claims = []): UnencryptedToken
	{
		$now = CarbonImmutable::now();
		$ttl = config('jwt.ttl');
		$expiration = $now->modify("+{$ttl} minutes");
		$tokenBuilder = $this->config()->builder()
			->issuedBy(config('app.url'))
			->permittedFor(config('app.url'))
			->identifiedBy(Str::uuid7())
			->relatedTo($userPublicID)
			->issuedAt($now)
			->canOnlyBeUsedAfter($now)
			->expiresAt($expiration);

		foreach ($claims as $claim => $value) {
			$tokenBuilder = $tokenBuilder->withClaim($claim, $value);
		}

		return $tokenBuilder->getToken($this->configuration->signer(), $this->configuration->signingKey());
	}


}
