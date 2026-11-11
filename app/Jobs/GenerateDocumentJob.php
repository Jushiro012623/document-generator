<?php

namespace App\Jobs;

use App\Action\Documents\GenerateDocumentAction;
use App\Enums\DocumentStatus;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateDocumentJob implements ShouldQueue
{
	use Dispatchable;
	use InteractsWithQueue;
	use Queueable;
	use SerializesModels;

	public int $tries = 3;

	public int $timeout = 120;

	public bool $failOnTimeout = true;

	public function __construct(
		public readonly int $documentId
	) {}

	public function backoff(): array
	{
		return [10, 30, 60];
	}

	public function handle(
		GenerateDocumentAction $action
	): void {
		$document = Document::findOrFail($this->documentId);

		if (in_array($document->status, [
			DocumentStatus::COMPLETED,
			DocumentStatus::CANCELLED,
		], true)) {
			return;
		}

		$action->execute($document);
	}

	public function failed(?Throwable $exception): void
	{
		Document::whereKey($this->documentId)->update([
			'status' => DocumentStatus::FAILED,
			'error_message' => $exception?->getMessage()
				?? 'Document generation failed.',
			'completed_at' => now(),
		]);
	}
}