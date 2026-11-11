<?php

namespace App\Services\Documents\Renderers;

use App\Enums\DocumentFormat;
use App\Models\TemplateVersion;
use App\Services\Documents\Contracts\DocumentRendererInterface;
use App\Services\Documents\GotenbergService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;

class DocxRenderer implements DocumentRendererInterface
{
	public function __construct(private readonly GotenbergService $gotenberg)
	{
	}

	public function render(TemplateVersion $version, array $data, DocumentFormat $outputFormat): string
	{
		if (!$version->storage_path) {
			throw new RuntimeException('DOCX template file is missing.');
		}

		$disk = Storage::disk(config('document-generation.template_disk'));

		if (!$disk->exists($version->storage_path)) {
			throw new RuntimeException('DOCX template file was not found.');
		}

		$directory = config('document-generation.temporary_directory');

		if (!is_dir($directory)) {
			mkdir($directory, 0700, true);
		}

		$source = tempnam($directory, 'template_') . '.docx';
		$output = tempnam($directory, 'generated_') . '.docx';

		file_put_contents($source, $disk->get($version->storage_path));

		try {
			$processor = new TemplateProcessor($source);

			$processor->setValues($this->flattenData($data));

			$processor->saveAs($output);

			if ($outputFormat === DocumentFormat::DOCX) {
				return $output;
			}

			if ($outputFormat === DocumentFormat::PDF) {
				return $this->gotenberg->convertOfficeToPdf($output);
			}

			throw new RuntimeException(
				'Unsupported output format for DOCX templates.'
			);
		} finally {
			@unlink($source);
		}
	}

	private function flattenData(array $data, string $prefix = ''): array
	{
		$result = [];

		foreach ($data as $key => $value) {
			$name = $prefix === ''
				? $key
				: $prefix . '.' . $key;

			if (is_array($value)) {
				$result += $this->flattenData($value, $name);
				continue;
			}

			if (is_scalar($value) || $value === null) {
				$result[$name] = (string)($value ?? '');
			}
		}

		return $result;
	}
}