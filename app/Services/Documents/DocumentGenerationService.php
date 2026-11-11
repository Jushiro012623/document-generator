<?php

namespace App\Services\Documents;

use App\Action\Documents\GenerateDocumentAction;
use App\Enums\DocumentStatus;
use App\Enums\TemplateStatus;
use App\Enums\TemplateVersionStatus;
use App\Jobs\GenerateDocumentJob;
use App\Models\Application;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

readonly class DocumentGenerationService
{
	public function __construct(private GenerateDocumentAction $action)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function generate(Application $application, array $payload): Document
	{
		$template = $application->templates()
			->whereKey($payload['template_id'])
			->whereStatus(TemplateStatus::ACTIVE)
			->firstOrFail();

		$versionQuery = $template->templateVersions();

		if (!empty($payload['template_version_id'])) {
			$versionQuery->whereKey($payload['template_version_id']);
		}

		$version = $versionQuery
			->whereStatus(TemplateVersionStatus::PUBLISHED)
			->orderByDesc('version')
			->firstOrFail();

		$data = $payload['data'] ?? [];

		$validator = Validator::make($data, $version->data_schema ?? []);
		$validator->validate();

		$mode = $payload['mode'] ?? 'async';

		$document = DB::transaction(function () use ($application, $template, $version, $payload, $data) {
			return $application->document()->create([
				'template_id' => $template->id,
				'template_version_id' => $version->id,
				'reference' => 'DOC-' . strtoupper(str()->random(16)),
				'format' => $payload['format'],
				'status' => DocumentStatus::PENDING,
				'generation_data' => $data,
				'requested_at' => now(),
			]);
		});

		if ($mode === 'sync') {
			try {
				$this->action->execute($document);
				return $document->fresh();
			} catch (Throwable $exception) {
				$document->update([
					'status' => DocumentStatus::FAILED,
					'error_message' => $exception->getMessage(),
					'completed_at' => now(),
				]);
				throw $exception;
			}
		}

		GenerateDocumentJob::dispatch($document->id)->onQueue('document-generation')->afterCommit();

		return $document;
	}
}