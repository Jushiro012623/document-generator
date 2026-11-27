<?php

use App\Services\Jwt\Drivers\Lcobucci\LcobucciService;

return [
	'default' => env('JWT_DRIVER', 'lcobucci'),

	'secret' => env('JWT_SECRET'),

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
];
