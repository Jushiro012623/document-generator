<?php

namespace App\Services;

use App\Context\AppContext;
use App\Enums\TemplateVersionStatus;
use App\Models\Application;
use App\Models\Template;
use App\Models\TemplateVersion;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class TemplateVersionService
{
	public function findAll(Application $application, string $template): LengthAwarePaginator
	{
		$template = $application->templates()->findOrFail($template);
		return $template->versions()->latest('version')->paginate();
	}

	/**
	 * @throws \Throwable
	 */
	public function create(Request $request, Application $application, string $template): TemplateVersion
	{
		$file = $request->file('file');
		$path = null;

		return DB::transaction(function () use ($request, $application, $template, $file, &$path) {
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
	}

	public function findOne(Application $application, string $template, string $version): TemplateVersion
	{
		$template = $application->templates()->findOrFail($template);

		return $template->versions()->findOrFail($version);
	}

	public function updateOne(Request $request, Application $application, string $template, string $version): void
	{
		$version = $this->findOne($application, $template, $version);

		if (!$version->status == TemplateVersionStatus::DRAFT) {
			throw new ConflictHttpException('Only draft versions can be updated.');
		}

		$version->update($request->validated());
	}

	public function publish(Application $application, string $template, string $version): void
	{
		$version = $this->findOne($application, $template, $version);

		if (!$version->status == TemplateVersionStatus::DRAFT) {
			throw new ConflictHttpException('Only draft versions can be published.');
		}

		# TODO Add publishing validation here before changing the status.
		$version->update(['status' => TemplateVersionStatus::PUBLISHED, 'published_at' => now(),]);

	}

	public function archive(Application $application, string $template, string $version): void
	{
		$version = $this->findOne($application, $template, $version);

		if ($version->status == TemplateVersionStatus::ARCHIVED) {
			throw new ConflictHttpException('Template version is already archived.');
		}

		$version->update(['status' => TemplateVersionStatus::ARCHIVED]);
	}
}
