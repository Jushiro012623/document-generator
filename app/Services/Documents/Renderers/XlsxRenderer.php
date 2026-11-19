<?php

namespace App\Services\Documents\Renderers;

use App\Enums\DocumentFormat;
use App\Models\TemplateVersion;
use App\Services\Documents\Contracts\DocumentRendererInterface;
use App\Services\Documents\GotenbergService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;

class XlsxRenderer implements DocumentRendererInterface
{
	public function __construct(private readonly GotenbergService $gotenberg)
	{
	}

	public function render(TemplateVersion $version, array $data, DocumentFormat $format): string
	{
		if (!$version->storage_path) {
			throw new RuntimeException('XLSX template file is missing.');
		}

		$disk = Storage::disk(
			config('document-generation.template_disk')
		);

		if (!$disk->exists($version->storage_path)) {
			throw new RuntimeException('XLSX template file was not found.');
		}

		$directory = config('document-generation.temporary_directory');

		if (!is_dir($directory)) {
			mkdir($directory, 0700, true);
		}

		$source = tempnam($directory, 'template_') . '.xlsx';
		$output = tempnam($directory, 'generated_') . '.xlsx';

		file_put_contents($source, $disk->get($version->storage_path));

		try {
			$spreadsheet = IOFactory::load($source);

			$values = $this->flattenData($data);

			foreach ($spreadsheet->getAllSheets() as $sheet) {
				foreach ($sheet->getRowIterator() as $row) {
					foreach ($row->getCellIterator() as $cell) {
						$value = $cell->getValue();

						if (!is_string($value)) {
							continue;
						}

						$replaced = preg_replace_callback(
							'/\{\{\s*([a-zA-Z0-9_.-]+)\s*\}\}/',
							function ($matches) use ($values) {
								return $values[$matches[1]] ?? $matches[0];
							},
							$value
						);

						if ($replaced !== $value) {
							$cell->setValueExplicit($replaced, DataType::TYPE_STRING);
						}
					}
				}
			}

			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

			$writer->save($output);

			$spreadsheet->disconnectWorksheets();

			if ($format === DocumentFormat::XLSX) {
				return $output;
			}

			if ($format === DocumentFormat::PDF) {
				return $this->gotenberg->convertOfficeToPdf($output);
			}

			throw new RuntimeException('Unsupported output format for XLSX templates.');
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