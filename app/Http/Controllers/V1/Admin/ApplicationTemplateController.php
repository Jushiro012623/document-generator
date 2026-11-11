<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use App\Http\Resources\TemplateResource;
use App\Models\Application;
use App\Models\Template;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class ApplicationTemplateController extends Controller
{
	use AuthorizesRequests;

	public function index(Application $application)
	{
		$templates = TemplateResource::collection($application->templates()->paginate());
		return response()->success('Templates Retrieved Successfully', $templates);
	}

	public function store(TemplateRequest $request, Application $application)
	{
		$template = TemplateResource::make($application->templates()->create($request->validated()));
		return response()->success('Template created successfully.', $template, Response::HTTP_CREATED);
	}

	public function show(string $template, Application $application)
	{
		$template = TemplateResource::make($application->templates()->findOrFail($template));
		return response()->success('Template Retrieved Successfully', $template);
	}

	public function update(TemplateRequest $request, string $template, Application $application)
	{
		$application->templates()->findOrFail($template)->update($request->validated());
		return response()->success("Template Updated Successfully");
	}

	public function destroy(string $template, Application $application)
	{
		$application->templates()->findOrFail($template)->delete();
		return response()->success("Template Deleted Successfully", httpStatus: Response::HTTP_NO_CONTENT);
	}
}
