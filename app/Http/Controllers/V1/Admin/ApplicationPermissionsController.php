<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationPermissionRequest;
use App\Http\Resources\ApplicationPermissionResource;
use App\Models\Application;
use Symfony\Component\HttpFoundation\Response;

class ApplicationPermissionsController extends Controller
{
	public function index(Application $application)
	{
		$applications = ApplicationPermissionResource::collection($application->permissions()->paginate());
		return response()->success("Application Permissions Retrieved Successfully", $applications);
	}

	public function store(ApplicationPermissionRequest $request, Application $application)
	{
		$permission = $application->permissions()->create($request->validated());

		return response()->success("Application Permission Created Successfully",
			ApplicationPermissionResource::make($permission),
			Response::HTTP_CREATED);

	}

	public function show(Application $application, string $permission)
	{
		$permission = ApplicationPermissionResource::make($application->permissions()->findOrFail($permission));
		return response()->success("Application Permission Retrieved Successfully", $permission);
	}

	public function update(ApplicationPermissionRequest $request, Application $application, string $permission)
	{
		$application->permissions()->findOrFail($permission)->update($request->validated());
		return response()->success("Application Permission Updated Successfully");
	}

	public function destroy(Application $application, string $permission)
	{
		$application->permissions()->findOrFail($permission)->forceDelete();
		return response()->success("Application Permission Deleted Successfully", httpStatus: Response::HTTP_NO_CONTENT);
	}
}
