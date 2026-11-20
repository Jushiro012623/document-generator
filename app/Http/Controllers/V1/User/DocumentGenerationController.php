<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\ApplicationPermission;
use App\Enums\DocumentStatus;
use App\Context\AppContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentGenerationRequest;
use App\Models\Application;
use App\Models\Document;
use App\Services\Documents\DocumentGenerationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class DocumentGenerationController extends Controller
{
	public function __construct(
		private readonly DocumentGenerationService $service,
	)
	{
	}

	/**
	 * @throws \Throwable
	 */
	public function store(DocumentGenerationRequest $request, AppContext $context)
	{
		Gate::authorize('document-generator', [$context->app, ApplicationPermission::DOCUMENT_GENERATE->value]);

		$document = $this->service->generate($context->app, $request->validated());
		$isCompleted = $document->status === DocumentStatus::COMPLETED;

		return response()->success(
			message: $isCompleted ? 'Document generated successfully.' : 'Document generation queued.',
			data: [
				'id' => $document->id,
				'reference' => $document->reference,
			],
			httpStatus: $isCompleted ? Response::HTTP_OK : Response::HTTP_ACCEPTED);
	}

	public function download(Document $document)
	{
		if (!$document->status == DocumentStatus::COMPLETED) {
			throw new ConflictHttpException("Document is not ready for download.");
		}

		$url = Storage::disk($document->storage_disk)->temporaryUrl($document->storage_path, now()->addMinutes(10));

		return response()->success(
			message: "Document downloaded successfully.",
			data: [
				'download_url' => $url,
				'expires_at' => now()->addMinutes(10)->toISOString(),
			]);
	}
}