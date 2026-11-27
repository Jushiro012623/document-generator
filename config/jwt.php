<?php

use App\Services\Jwt\Drivers\Lcobucci\LcobucciService;
use Lcobucci\JWT\Signer\Hmac\Sha256;

return [
	'default' => env('JWT_DRIVER', 'lcobucci'),

	'secret' => env('JWT_SECRET'),

	'signer' => Sha256::class,

	'leeway' => env('JWT_LEEWAY', 60),

	'ttl' => env('JWT_TTL', 60),

	'drivers' => [
		'lcobucci' => LcobucciService::class,
	],

	'required_claims' => [
		'iss',
		'iat',
		'aud',
		'exp',
		'nbf',
		'sub',
		'jti',
		'token_version'
	],

	'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

	'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
];
