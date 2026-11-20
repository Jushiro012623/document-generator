<?php

namespace App\Http\Controllers\V1\Admin;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentGenerationRequest;
use App\Models\Application;
use App\Services\Documents\DocumentGenerationService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AdminDocumentGenerationController extends Controller
{
	public function __construct(
		private readonly DocumentGenerationService $service,
	)
	{
	}

	/**
	 * @throws \Throwable
	 */
	public function store(DocumentGenerationRequest $request, Application $application)
	{
		$document = $this->service->generate($application, $request->validated());
		
		$isCompleted = $document->status === DocumentStatus::COMPLETED;

		return response()->success(
			message: $isCompleted ? 'Document generated successfully.' : 'Document generation queued.',
			data: [
				'id' => $document->id,
				'reference' => $document->reference,
			],
			httpStatus: $isCompleted ? Response::HTTP_OK : Response::HTTP_ACCEPTED);
	}

	public function download(Application $application, string $document)
	{
		$url = $this->service->download($application, $document);

		return response()->success(
			message: "Document downloaded successfully.",
			data: [
				'download_url' => $url,
				'expires_at' => now()->addMinutes(10)->toISOString(),
			]);
	}
}
