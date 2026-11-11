<?php

namespace App\Services\Documents\Renderers;

use App\Enums\DocumentFormat;
use App\Models\TemplateVersion;
use App\Services\Documents\Contracts\DocumentRendererInterface;
use App\Services\Documents\GotenbergService;
use Mustache_Engine;
use RuntimeException;

class HtmlRenderer implements DocumentRendererInterface
{
	public function __construct(private readonly GotenbergService $gotenberg)
	{
	}

	public function render(TemplateVersion $version, array $data, DocumentFormat $outputFormat): string
	{
		if ($outputFormat !== DocumentFormat::PDF) {
			throw new RuntimeException('HTML templates currently support PDF output only.'
			);
		}

		$content = $version->content ?? '';

		if ($content === '') {
			throw new RuntimeException('HTML template content is empty.');
		}

		if (preg_match('/\{\{\{|{{&/', $content)) {
			throw new RuntimeException('Unescaped HTML template variables are not supported.');
		}

		$mustache = new Mustache_Engine();

		$rendered = $mustache->render($content, $data);

		$styles = $version->styles ?? '';

		$html = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>{$styles}</style>
        </head>
        <body>
            {$rendered}
        </body>
        </html>
        HTML;

		return $this->gotenberg->convertHtmlToPdf($html);
	}
}