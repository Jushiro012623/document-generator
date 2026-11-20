<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateVersionRequest;
use App\Http\Resources\TemplateVersionResource;
use App\Models\Application;
use App\Services\TemplateVersionService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApplicationTemplateVersionController extends Controller
{
	public function __construct(private readonly TemplateVersionService $service)
	{
	}

	public function index(Application $application, string $template)
	{
		$versions = $this->service->findAll($application, $template);

		return response()->success(
			message: "Template Version Retrieved Successfully",
			data: TemplateVersionResource::collection($versions)
		);
	}

	/**
	 * @throws Throwable
	 */
	public function store(TemplateVersionRequest $request, Application $application, string $template)
	{
		$version = $this->service->create($request, $application, $template);

		return response()->success(
			message: "Template Version Created Successfully",
			data: TemplateVersionResource::make($version),
			httpStatus: Response::HTTP_CREATED
		);
	}

	public function show(Application $application, string $template, string $version)
	{
		$version = $this->service->findOne($application, $template, $version);

		return response()->success(
			message: 'Template version retrieved successfully.',
			data: TemplateVersionResource::make($version)
		);
	}

	public function update(TemplateVersionRequest $request, Application $application, string $template, string $version)
	{
		$this->service->updateOne($request, $application, $template, $version);

		return response()->success('Template version updated successfully.');
	}

	public function publish(Application $application, string $template, string $version)
	{
		$this->service->publish($application, $template, $version);

		return response()->success('Template version published successfully.');
	}

	public function archive(Application $application, string $template, string $version)
	{
		$this->service->archive($application, $template, $version);

		return response()->success('Template version archived successfully.');
	}
}
