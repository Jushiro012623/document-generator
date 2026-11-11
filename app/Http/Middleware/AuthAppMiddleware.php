<?php

namespace App\Http\Middleware;

use App\Enums\ApplicationStatus;
use App\Models\ApplicationCredential;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthAppMiddleware
{
	/**
	 * @throws AuthenticationException
	 * @throws AuthorizationException
	 */
	public function handle(Request $request, Closure $next): JsonResponse
	{
		$token = $request->bearerToken();

		if (!$token || !str_contains($token, '.')) {
			throw new AuthenticationException();
		}

		[$keyPrefix, $secret] = explode('.', $token, 2);

		if (!$keyPrefix || !$secret) {
			throw new AuthenticationException();
		}

		$credential = ApplicationCredential::query()
			->with('application')
			->where('key_prefix', $keyPrefix)
			->first();

		if (!$credential || !hash_equals($credential->secret_hash, hash('sha256', $secret))) {
			throw new AuthenticationException("Invalid Credentials");
		}

		if (
			$credential->revoked_at !== null ||
			($credential->expires_at !== null && $credential->expires_at->isPast())
		) {
			throw new AuthenticationException('AppContext credential is expired or revoked.');
		}

		$application = $credential->application;

		if (!$application || $application->status !== ApplicationStatus::ACTIVE) {
			throw new AuthorizationException('AppContext is inactive.');
		}

		$credential->forceFill(['last_used_at' => now(),])->save();

		$request->attributes->set('application', $application);
		$request->attributes->set('application_credential', $credential);

		return $next($request);
	}
}
