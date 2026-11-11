<?php

return [
	'gotenberg' => [
		'url' => env('GOTENBERG_URL', 'http://gotenberg:3000'),
		'timeout' => (int)env('GOTENBERG_TIMEOUT', 120),
	],

	'template_disk' => env('TEMPLATE_STORAGE_DISK', 'file'),

	'document_disk' => env('DOCUMENT_STORAGE_DISK', 'file'),

	'temporary_directory' => storage_path('app/document-generation'),

	'max_input_size' => 10 * 1024 * 1024,
];
