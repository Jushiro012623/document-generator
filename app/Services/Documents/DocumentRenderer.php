<?php

namespace App\Services\Documents;

use App\Enums\DocumentFormat;
use App\Enums\TemplateFormat;
use App\Models\TemplateVersion;
use App\Services\Documents\Renderers\DocxRenderer;
use App\Services\Documents\Renderers\HtmlRenderer;
use App\Services\Documents\Renderers\XlsxRenderer;
use RuntimeException;

readonly class DocumentRenderer
{
	public function __construct(private HtmlRenderer $html, private DocxRenderer $docx, private XlsxRenderer $xlsx)
	{
	}

	public function render(TemplateVersion $version, array $data, DocumentFormat $outputFormat): string
	{
		return match ($version->format) {
			TemplateFormat::HTML => $this->html->render($version, $data, $outputFormat),

			TemplateFormat::DOCX => $this->docx->render($version, $data, $outputFormat),

			TemplateFormat::XLSX => $this->xlsx->render($version, $data, $outputFormat),

			default => throw new RuntimeException('Unsupported template format.'),
		};
	}
}