<?php

namespace App\Providers;

use App\Services\Jwt\Contracts\JwtDriverInterface;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use InvalidArgumentException;

class JwtServiceProvider extends ServiceProvider
{
	public function register(): void
	{

		$driver = config('jwt.default');
		$implementation = config("jwt.drivers.{$driver}");

		if (!$implementation || !class_exists($implementation)) {
			throw new InvalidArgumentException("Unsupported JWT driver [{$driver}].");
		}

		if ($driver === 'lcobucci') {
			$this->app->singleton(Configuration::class, function () {
				return Configuration::forSymmetricSigner(
					signer: new Sha256(),
					key: InMemory::base64Encoded(config('jwt.secret'))
				);
			});
		}

		$this->app->bind(JwtDriverInterface::class, $implementation);
	}

	public function boot(): void
	{
	}
}
