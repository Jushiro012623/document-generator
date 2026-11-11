<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class TemplateController extends Controller
{
	use AuthorizesRequests;

	public function index()
	{
		$this->authorize('view-any', Template::class);
		$templates = TemplateResource::collection(Template::with('application')->paginate());
		return response()->success('Templates Retrieved Successfully', $templates);
	}

	public function store(TemplateRequest $request)
	{
		$this->authorize('create', Template::class);
		$template = TemplateResource::make(Template::create($request->validated()));
		return response()->success('Template created successfully.', $template, Response::HTTP_CREATED);
	}

	public function show(Template $template)
	{
		$this->authorize('view', $template);
		return response()->success('Template Retrieved Successfully', TemplateResource::make($template));
	}

	public function update(TemplateRequest $request, Template $template)
	{
		$this->authorize('update', $template);
		$template->update($request->validated());
		return response()->success("Template Updated Successfully");
	}

	public function destroy(Template $template)
	{
		$this->authorize('delete', $template);
		$template->delete();
		return response()->success("Template Deleted Successfully", httpStatus: Response::HTTP_NO_CONTENT);
	}
}
