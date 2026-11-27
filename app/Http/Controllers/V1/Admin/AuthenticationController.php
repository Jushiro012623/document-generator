<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\Jwt\JwtService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
	public function __construct(private readonly JwtService $jwtService)
	{
	}

	/**
	 * @throws AuthenticationException
	 * @throws \Throwable
	 */
	public function login(LoginRequest $request)
	{
		['identifier' => $identifier, 'password' => $password] = $request;

		$loginField = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		$user = User::where($loginField, $identifier)->first();

		if (!$user || !Hash::check($password, $user->password)) {
			throw new AuthenticationException('The credentials you provided are incorrect.');
		}

		$request->setUserResolver(fn () => $user);

		$accessToken = $this->jwtService->issue($user->id, $user->token_version);

		return response()->success('Logged In Successfully', [
			'access_token' => $accessToken,
			'user' => $user->toResource()
		]);
	}

	public function logout(Request $request)
	{
		$this->jwtService->revoke($request->bearerToken());
		return response()->noContent();
	}
}
