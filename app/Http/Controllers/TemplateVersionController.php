<?php

namespace App\Http\Controllers;

use App\Context\AppContext;
use App\Http\Requests\TemplateVersionRequest;
use App\Http\Resources\TemplateVersionResource;
use App\Models\Template;
use App\Services\TemplateVersionService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TemplateVersionController extends Controller
{
	public function __construct(private readonly TemplateVersionService $service)
	{
	}

	public function index(AppContext $appContext, Template $template)
	{
		$versions = $this->service->findAll($appContext->app, $template);

		return response()->success(
			message: "Template Version Retrieved Successfully",
			data: TemplateVersionResource::collection($versions)
		);
	}

	/**
	 * @throws Throwable
	 */
	public function store(TemplateVersionRequest $request, AppContext $appContext, Template $template)
	{
		$version = $this->service->create($request, $appContext->app, $template);

		return response()->success(
			message: "Template Version Created Successfully",
			data: TemplateVersionResource::make($version),
			httpStatus: Response::HTTP_CREATED
		);

	}

	public function show(AppContext $appContext, string $template, string $version)
	{
		$version = $this->service->findOne($appContext->app, $template, $version);

		return response()->success(
			message: 'Template version retrieved successfully.',
			data: TemplateVersionResource::make($version)
		);
	}

	public function update(TemplateVersionRequest $request, AppContext $appContext, string $template, string $version)
	{
		$this->service->updateOne($request, $appContext->app, $template, $version);

		return response()->success('Template version updated successfully.');
	}

	public function publish(AppContext $appContext, string $template, string $version)
	{
		$this->service->publish($appContext->app, $template, $version);

		return response()->success('Template version published successfully.');
	}

	public function archive(AppContext $appContext, string $template, string $version)
	{
		$this->service->archive($appContext->app, $template, $version);

		return response()->success('Template version archived successfully.');
	}
}
