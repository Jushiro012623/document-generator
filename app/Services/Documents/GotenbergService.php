<?php

namespace App\Services\Documents;

use RuntimeException;
use Illuminate\Support\Facades\Http;

class GotenbergService
{
	/**
	 * Convert the supplied HTML into PDF.
	 *
	 * The method writes the given HTML to a temporary file, calls the internal
	 * convert routine with the appropriate API endpoint, and cleans up the
	 * temporary file in a finally block so the file is removed regardless of
	 * success or failure.
	 *
	 * @param string $html The HTML string to convert to PDF.
	 * @return string The result of the conversion, typically the PDF content or a path.
	 */
	public function convertHtmlToPdf(string $html): string
	{
		$path = $this->temporaryPath('html');

		file_put_contents($path, $html);

		try {
			return $this->convert('/forms/chromium/convert/html', $path, 'index.html');
		} finally {
			@unlink($path);
		}
	}

	/**
	 * Convert an office document to PDF.
	 *
	 * Delegates the conversion to the internal helper, targeting the LibreOffice
	 * conversion endpoint and forwarding the original file name. The method returns
	 * the result of that conversion – usually the PDF contents or the path to the
	 * generated PDF file.
	 *
	 * @param string $path Path to the source office document.
	 * @return string The resulting PDF content or file path.
	 */
	public function convertOfficeToPdf(string $path): string
	{
		return $this->convert('/forms/libreoffice/convert', $path, basename($path));
	}

	/**
	 * Sends a document to the Gotenberg API for conversion and stores the result.
	 *
	 * The method builds an HTTP POST request to the specified Gotenberg endpoint,
	 * attaching the file located at $path under the name $filename. It uses a
	 * timeout defined in the application configuration. If the request fails,
	 * it throws a RuntimeException containing the response body for debugging.
	 *
	 * On success, the binary response is written to a newly created temporary file
	 * in the PDF directory and the path to this file is returned.
	 *
	 * @param string $endpoint The API endpoint relative to the Gotenberg base URL.
	 * @param string $path The filesystem path to the file to be sent.
	 * @param string $filename The filename to associate with the attached file.
	 *
	 * @return string  The filesystem path to the temporary PDF file containing
	 *                 the conversion result.
	 *
	 * @throws RuntimeException if the HTTP request is not successful.
	 */
	private function convert(string $endpoint, string $path, string $filename): string
	{
		$response = Http::timeout(config('document-generation.gotenberg.timeout'))
			->attach('files', file_get_contents($path), $filename)
			->post(rtrim(config('document-generation.gotenberg.url'), '/') . $endpoint);

		if (!$response->successful()) {
			throw new RuntimeException('Document conversion failed: ' . $response->body());
		}

		$output = $this->temporaryPath('pdf');

		file_put_contents($output, $response->body());

		return $output;
	}

	/**
	 * Generates a unique temporary file path for the given extension.
	 *
	 * The method ensures the configured temporary directory exists, creating it
	 * with restricted permissions if necessary. It then returns a fully‑qualified
	 * path consisting of that directory, a unique prefix, and the supplied
	 * extension, suitable for writing temporary files during document generation.
	 *
	 * @param string $extension The file extension to append to the generated path.
	 * @return string A filesystem path to a new, unique temporary file.
	 */
	private function temporaryPath(string $extension): string
	{
		$directory = config('document-generation.temporary_directory'
		);

		if (!is_dir($directory)) {
			mkdir($directory, 0700, true);
		}

		return $directory . '/' . uniqid('document_', true) . '.' . $extension;
	}
}
