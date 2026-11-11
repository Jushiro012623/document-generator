<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationCredentialsRequest;
use App\Http\Resources\ApplicationCredentialsResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\ApplicationCredential;
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
		$validated = $request->validated();
		$secret = bin2hex(random_bytes(32));

		$validated['key_prefix'] = sprintf('DG_%s_%s', config('app.env'), strtoupper(bin2hex(random_bytes(6))));
		$validated['secret_hash'] = hash('sha256', $secret);

		$credentials = $application->credentials()->create($validated);

		return response()->success("Application Credentials Created Successfully", [
			...$credentials->only(['name', 'key_prefix']),
			'secret' => $secret
		], Response::HTTP_CREATED);
	}

	public function show(Application $application, string $credentials)
	{
		$application = ApplicationCredentialsResource::make($application->credentials()->findOrFail($credentials));
		return response()->success("Application Credential Retrieved Successfully", $application);
	}

	public function update(ApplicationCredentialsRequest $request, Application $application, string $credentials)
	{
		$application->credentials()->findOrFail($credentials)->update($request->validated());
		return response()->success("Application Credential Updated Successfully");
	}

	public function destroy(Application $application, string $credentials)
	{
		$application->credentials()->findOrFail($credentials)->delete();
		return response()->success("Application Credential Deleted Successfully", httpStatus: Response::HTTP_NO_CONTENT);
	}
}
