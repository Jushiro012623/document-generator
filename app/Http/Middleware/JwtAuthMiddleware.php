<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Jwt\JwtService;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtAuthMiddleware
{
	/**
	 * @throws AuthenticationException
	 */
	public function handle(Request $request, Closure $next)
	{
		$jwt = app(JwtService::class);
		
		$token = $request->bearerToken() ?? '';
		$plainToken = $jwt->verify($token);

		$sub = $plainToken->get('sub');

		if (!$user = User::find($sub)) {
			throw new AuthenticationException('Unable to authenticate the request.');
		}

		if ($plainToken->get('token_version') !== $user->token_version) {
			throw new AuthenticationException('The authentication token is no longer valid.');
		}

		Auth::setUser($user);
		$request->setUserResolver(fn () => $user);
		$request->attributes->set('token', $plainToken->claims);

		return $next($request);
	}
}
