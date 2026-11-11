<?php

namespace App\Services\Documents\Contracts;

use App\Enums\DocumentFormat;
use App\Models\TemplateVersion;

interface
DocumentRendererInterface
{
	public function render(
		TemplateVersion $version,
		array $data,
		DocumentFormat $format,
	): string;
}
