<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use function Pest\Laravel\instance;

class ApiExceptionHandler
{
	public static function register(Exceptions $exceptions): void
	{
		self::registerReporting($exceptions);
		self::registerRendering($exceptions);
	}

	private static function registerReporting(Exceptions $exceptions): void
	{
		$exceptions->report(function (Throwable $e): void {
			if (
				$e instanceof ValidationException ||
				$e instanceof AuthenticationException ||
				$e instanceof AuthorizationException ||
				$e instanceof NotFoundHttpException ||
				$e instanceof GoneHttpException ||
				$e instanceof ConflictHttpException ||
				$e instanceof ResourceNotFoundException ||
				$e instanceof ThrottleRequestsException
			) {
				return;
			}

			Log::error('API Exception', [
				'trace_id' => (string)str()->uuid(),
				'url' => request()->getRequestUri(),
				'method' => request()->method(),
				'status' => 500,
				'exception' => class_basename($e),
				'message' => $e->getMessage(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
			]);
		});
	}

	private static function registerRendering(Exceptions $exceptions): void
	{
		$exceptions->shouldRenderJsonWhen(
			function (Request $request, Throwable $e): bool {
				return $request->is('api/*') || $request->expectsJson();
			}
		);

		$exceptions->render(function (Throwable $e) {

			if ($e instanceof ValidationException) {
				$errors = [];

				foreach ($e->errors() as $field => $messages) {
					foreach ($messages as $message) {
						$errors[] = [
							'field' => $field,
							'message' => $message,
						];
					}
				}

				return response()->fail(
					'One or more validation errors occurred.',
					['errors' => $errors],
					HttpResponse::HTTP_UNPROCESSABLE_ENTITY
				);
			}

			if ($e instanceof AuthenticationException) {
				$detail = !blank($e->getMessage())
					? $e->getMessage()
					: 'Authentication failed. Valid credentials are required to access this resource.';

				return response()->fail(
					$detail,
					[],
					HttpResponse::HTTP_UNAUTHORIZED
				);
			}

			if ($e instanceof UnauthorizedHttpException) {
				$detail = !blank($e->getMessage())
					? $e->getMessage()
					: 'You are not authorized to perform this action.';

				return response()->fail(
					$detail,
					[],
					HttpResponse::HTTP_UNAUTHORIZED
				);
			}

			if (
				$e instanceof AuthorizationException ||
				$e instanceof AccessDeniedHttpException
			) {
				return response()->fail(
					$e->getMessage() ?: 'You do not have permission to access this resource.',
					[],
					HttpResponse::HTTP_FORBIDDEN
				);
			}

			if ($e instanceof ConflictHttpException) {
				return response()->fail(
					$e->getMessage() ?: 'The request conflicts with the current state of the resource.',
					[],
					HttpResponse::HTTP_CONFLICT
				);
			}

			if ($e instanceof ResourceNotFoundException) {
				return response()->fail($e->getMessage() ?: 'The requested resource was not found.', [], HttpResponse::HTTP_NOT_FOUND);
			}

			if ($e instanceof NotFoundHttpException) {
				return response()->fail(
					'The requested resource was not found.',
					[],
					HttpResponse::HTTP_NOT_FOUND
				);
			}

			if ($e instanceof GoneHttpException) {
				return response()->fail(
					$e->getMessage() ?: 'This resource is no longer available.',
					[],
					HttpResponse::HTTP_GONE
				);
			}

			if ($e instanceof MethodNotAllowedHttpException) {
				return response()->fail(
					'The ' . request()->method() . ' method is not allowed for this endpoint.',
					[
						'allowed_methods' => array_map(
							'trim',
							explode(',', $e->getHeaders()['Allow'] ?? '')
						),
					],
					HttpResponse::HTTP_METHOD_NOT_ALLOWED
				);
			}

			if ($e instanceof QueryException) {
				$extensions = [];

				if (!app()->isProduction()) {
					$extensions['debug'] = [
						'mysql_error_code' => $e->errorInfo[1] ?? null,
						'sql_state' => $e->errorInfo[0] ?? null,
						'message' => $e->getMessage(),
					];
				}

				return response()->fail(
					app()->isProduction()
						? "An unexpected error occurred while processing the request."
						: 'A database operation failed while processing the request.',
					$extensions,
					HttpResponse::HTTP_INTERNAL_SERVER_ERROR
				);
			}

			if ($e instanceof ThrottleRequestsException) {
				return response()->fail(
					'Too many requests were received. Please try again later.',
					[
						'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
					],
					HttpResponse::HTTP_TOO_MANY_REQUESTS
				)->withHeaders($e->getHeaders());
			}

			$debug = [];

			if (app()->environment('local', 'staging')) {
				$debug['exceptions'] = class_basename($e);
				$debug['detail'] = $e->getMessage();
				$debug['file'] = $e->getFile();
				$debug['line'] = $e->getLine();
			}

			return response()->fail(
				'An unexpected error occurred while processing the request.',
				$debug,
				HttpResponse::HTTP_INTERNAL_SERVER_ERROR
			);
		});
	}
}
