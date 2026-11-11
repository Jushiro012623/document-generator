<?php

namespace App\Action\Documents;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Services\Documents\DocumentRenderer;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

readonly class GenerateDocumentAction
{
	public function __construct(
		private DocumentRenderer $renderer,
	)
	{
	}

	public function execute(Document $document): void
	{
		$attemptNumber = (
				$document->documentGenerationAttempts()->max('attempt_number') ?? 0
			) + 1;

		$attempt = $document->documentGenerationAttempts()->create([
			'attempt_number' => $attemptNumber,
			'status' => DocumentStatus::PROCESSING,
			'started_at' => now(),
		]);

		$temporaryPath = null;

		try {
			$document->update([
				'status' => DocumentStatus::PROCESSING,
				'started_at' => now(),
				'error_message' => null,
			]);

			$document->loadMissing('templateVersion');

			$version = $document->templateVersion;

			if (!$version || $version->status->value !== 'PUBLISHED') {
				throw new RuntimeException(
					'The requested template version is not published.'
				);
			}

			$temporaryPath = $this->renderer->render(
				$version,
				$document->generation_data,
				$document->format
			);

			if (!is_file($temporaryPath)) {
				throw new RuntimeException(
					'The rendering engine did not produce an output file.'
				);
			}

			$extension = strtolower($document->format->value);

			$filename = $document->filename
				?: $document->reference . '.' . $extension;

			$storagePath = sprintf(
				'documents/%s/%s',
				$document->application_id,
				$filename
			);

			$disk = Storage::disk(
				config('document-generation.document_disk')
			);

			$stream = fopen($temporaryPath, 'rb');

			try {
				$stored = $disk->put(
					$storagePath,
					$stream
				);
			} finally {
				if (is_resource($stream)) {
					fclose($stream);
				}
			}

			if (!$stored) {
				throw new RuntimeException(
					'Unable to store generated document.'
				);
			}

			$mimeType = match ($extension) {
				'pdf' => 'application/pdf',
				'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				default => 'application/octet-stream',
			};

			$duration = (int)(
			now()->diffInMilliseconds($attempt->started_at)
			);

			$document->update([
				'filename' => $filename,
				'storage_disk' => config(
					'document-generation.document_disk'
				),
				'storage_path' => $storagePath,
				'mime_type' => $mimeType,
				'file_size' => $disk->size($storagePath),
				'status' => DocumentStatus::COMPLETED,
				'completed_at' => now(),
				'error_message' => null,
			]);

			$attempt->update([
				'status' => DocumentStatus::COMPLETED,
				'completed_at' => now(),
				'duration_ms' => $duration,
			]);
		} catch (Throwable $exception) {
			$attempt->update([
				'status' => DocumentStatus::FAILED,
				'error_message' => $exception->getMessage(),
				'completed_at' => now(),
				'duration_ms' => (int)(
				now()->diffInMilliseconds($attempt->started_at)
				),
			]);

			report($exception);

			throw $exception;
		} finally {
			if ($temporaryPath && is_file($temporaryPath)) {
				@unlink($temporaryPath);
			}
		}
	}
}