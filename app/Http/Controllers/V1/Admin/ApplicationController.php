<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
	use AuthorizesRequests;

	public function index()
	{
//		$this->authorize('viewAny', AppContext::class);
		return response()->success("Applications Retrieved Successfully", ApplicationResource::collection(Application::paginate()));
	}

	/**
	 * @throws \Throwable
	 */
	public function store(ApplicationRequest $request)
	{
		$validated = $request->validated();
		$generatedCredentials = null;

		$application = DB::transaction(function () use ($validated, &$generatedCredentials): Application {
			$app = Application::create(Arr::except($validated, ['permissions', 'credentials']));

			$app->permissions()->createMany($validated['permissions'] ?? []);

			$generatedCredentials = collect($validated['credentials'] ?? [])
				->map(function (array $credential) {
					return [
						...$credential,
						'key_prefix' => sprintf('DG_%s_%s', config('app.env'), strtoupper(bin2hex(random_bytes(6)))),
						'secret_hash' => hash('sha256', $secret = bin2hex(random_bytes(32))),
						'secret' => $secret,
					];
				});

			$app->credentials()->createMany($generatedCredentials->map(fn ($credential) => Arr::except($credential, ['secret']))->all());

			return $app;
		});

		return response()->success(
			message: 'Application Created Successfully',
			data: [
				...ApplicationResource::make($application->load(['permissions', 'credentials',]))->toArray($request),
				'credentials' => $generatedCredentials->map(
					fn ($credential) => [
						'name' => $credential['name'],
						'key' => $credential['key_prefix'],
						'secret' => $credential['secret'],
					]
				),
			],
			httpStatus: Response::HTTP_CREATED
		);
	}

	public function show(Application $application)
	{
//		$this->authorize('view', $application);

		return response()->success("Application Retrieved Successfully", ApplicationResource::make($application));
	}

	public function update(ApplicationRequest $request, Application $application)
	{
//		$this->authorize('update', $application);

		$application->update($request->validated());

		return response("Application Updated Successfully");
	}

	public function destroy(Application $application)
	{
//		$this->authorize('delete', $application);

		$application->delete();

		return response()->success("Application Deleted Successfully");
	}
}
