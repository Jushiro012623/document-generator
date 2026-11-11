<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationCredentialsRequest;
use App\Http\Resources\ApplicationCredentialsResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Symfony\Component\HttpFoundation\Response;

class ApplicationCredentialsController extends Controller
{
	public function index(Application $application)
	{
		$applications = ApplicationCredentialsResource::collection($application->credentials()->paginate());
		return response()->success("Application Credentials Retrieved Successfully", $applications);
	}

	public function store(ApplicationCredentialsRequest $request, Application $application)
	{
		$generateCredentials = [
			''
		];
		$credentials = $application->credentials()->create($request->validated());
		return response()->success("Application Credentials Created Successfully", $credentials, Response::HTTP_CREATED);
	}
}
