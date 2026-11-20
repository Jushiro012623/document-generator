<?php

namespace App\Http\Controllers\V1\User;

use App\Context\AppContext;
use App\Enums\ApplicationPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TemplateController extends Controller
{
	public function index(AppContext $context)
	{
		Gate::authorize('document-generator', [
			$context->app,
			ApplicationPermission::TEMPLATE_VIEW->value
		]);

		$templates = $context->app->templates()->paginate();

		return response()->success(
			message: 'Templates Retrieved Successfully',
			data: TemplateResource::collection($templates)
		);
	}

	public function store(TemplateRequest $request, AppContext $context)
	{
		Gate::authorize('document-generator', [
			$context->app,
			ApplicationPermission::TEMPLATE_CREATE->value
		]);

		$template = $context->app->templates()->create($request->validated());

		return response()->success(
			message: 'Template created successfully.',
			data: TemplateResource::make($template),
			httpStatus: Response::HTTP_CREATED);
	}

	public function show(string $id, AppContext $context)
	{
		Gate::authorize('document-generator', [
			$context->app,
			ApplicationPermission::TEMPLATE_VIEW->value,
		]);

		$template = $context->app->templates()->findOrFail($id);

		return response()->success(
			message: 'Template Retrieved Successfully',
			data: TemplateResource::make($template)
		);
	}

	public function update(TemplateRequest $request, string $id, AppContext $context)
	{
		Gate::authorize('document-generator', [
			$context->app,
			ApplicationPermission::TEMPLATE_UPDATE->value
		]);

		$template = $context->app->templates()->findOrFail($id);

		$template->update($request->validated());

		return response()->success("Template Updated Successfully");
	}

	public function destroy(string $id, AppContext $context)
	{
		Gate::authorize('document-generator', [
			$context->app,
			ApplicationPermission::TEMPLATE_DELETE->value
		]);

		$template = $context->app->templates()->findOrFail($id);
		$template->delete();

		return response()->success(
			httpStatus: Response::HTTP_NO_CONTENT
		);
	}
}
