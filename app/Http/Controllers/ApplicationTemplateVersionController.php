<?php

namespace App\Http\Controllers;

use App\Enums\TemplateVersionStatus;
use App\Http\Requests\TemplateVersionRequest;
use App\Http\Resources\TemplateVersionResource;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class ApplicationTemplateVersionController extends Controller
{
	public function index(Application $application, string $template)
	{
		$template = $application->templates()->findOrFail($template);
		$versions = $template->versions()->latest('version')->paginate();
		return response()->success("Template Version Retrieved Successfully", TemplateVersionResource::collection($versions));
	}

	public function store(TemplateVersionRequest $request, Application $application, string $template)
	{
		$file = $request->file('file');
		$path = null;

		$version = DB::transaction(function () use ($request, $application, $template, $file, &$path) {
			$template = $application->templates()->lockForUpdate()->findOrFail($template);
			$nextVersion = ((int)$template->versions()->max('version')) + 1;

			if ($file) {
				$extension = strtolower($file->getClientOriginalExtension());

				$filename = sprintf('template-%d-version-%d.%s', $template->id, $nextVersion, $extension);

				$path = $file->storeAs("templates/{$template->id}/versions/{$nextVersion}", $filename, 'local');

				if (!$path) {
					throw new RuntimeException('Failed to upload template file.');
				}
			}
			return $template->versions()->create([
				...$request->validated(),
				'version' => $nextVersion,
				'storage_path' => $path
			]);
		});

		return response()->success("Template Version Created Successfully", TemplateVersionResource::make($version), Response::HTTP_CREATED);
	}

	public function show(Application $application, string $template, string $version)
	{
		$template = $application->templates()->findOrFail($template);

		$version = $template->versions()->findOrFail($version);

		return response()->success('Template version retrieved successfully.', TemplateVersionResource::make($version));
	}

	public function update(TemplateVersionRequest $request, Application $application, string $template, string $version)
	{
		$template = $application->templates()->findOrFail($template);

		$version = $template->versions()->findOrFail($version);

		if (!$version->status == TemplateVersionStatus::DRAFT) {
			throw new ConflictHttpException('Only draft versions can be updated.');
		}

		$version->update($request->validated());

		return response()->success('Template version updated successfully.');
	}

	public function publish(Application $application, string $template, string $version)
	{
		$template = $application->templates()->findOrFail($template);

		$version = $template->versions()->findOrFail($version);

		if (!$version->status == TemplateVersionStatus::DRAFT) {
			throw new ConflictHttpException('Only draft versions can be published.');
		}

		# TODO Add publishing validation here before changing the status.
		$version->update(['status' => TemplateVersionStatus::PUBLISHED, 'published_at' => now(),]);

		return response()->success('Template version published successfully.');
	}

	public function archive(Application $application, string $template, string $version)
	{
		$template = $application->templates()->findOrFail($template);

		$version = $template->versions()->findOrFail($version);

		if ($version->status == TemplateVersionStatus::ARCHIVED) {
			throw new ConflictHttpException('Template version is already archived.');
		}

		$version->update(['status' => TemplateVersionStatus::ARCHIVED]);
		return response()->success('Template version archived successfully.');
	}
}
