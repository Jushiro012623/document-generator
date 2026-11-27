<?php

namespace App\Providers;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
	public function register(): void
	{

	}

	public function boot(): void
	{
		Response::macro('success', function (
			string $message = 'success',
			mixed $data = [],
			int $httpStatus = 200,
			array $additional = [],
		): JsonResponse {

			$paginator = null;

			$httpStatus = $httpStatus >= 400 ? 200 : $httpStatus;

			$response = [
				'status' => $httpStatus,
				'message' => $message,
			];

			$response['data'] = $data ?? [];

			$response = array_merge($response, $additional ?? []);

			if ($data instanceof JsonResource && $data->resource instanceof LengthAwarePaginator) {
				$paginator = $data->resource;
			}

			if ($data instanceof LengthAwarePaginator) {
				$data->appends(request()->query());
				$paginator = $data;
				$response['data'] = $data->getCollection();
			}

			if ($paginator) {
				$pagination = PaginationResource::make($paginator)->resolve();
				$response = array_merge($response, $pagination);
			}


			return response()->json($response, $httpStatus);
		});

		Response::macro('cache', function ($cached) {
			return response()->json($cached['body'], $cached['status'], [...$cached['headers'], 'X-Cache' => 'HIT']);
		});

		Response::macro('fail', function (
			string $detail,
			array $errors = [],
			int $status = 400,
			?string $title = null,
		): JsonResponse {

			$status = $status >= 400 ? $status : 500;

			$problem = [
				'type' => "https://httpstatuses.io/$status",
				'title' => $title ?? \Illuminate\Http\Response::$statusTexts[$status] ?? 'Unknown Error',
				'status' => $status,
				'detail' => $detail,
				'instance' => request()->getRequestUri()
			];

			return response()->json(
				array_merge($problem, $errors),
				$status,
				[
					'Content-Type' => 'application/problem+json',
				]
			);
		});
	}
}
