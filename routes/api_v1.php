<?php

use App\Http\Controllers\V1\Admin\AdminDocumentGenerationController;
use App\Http\Controllers\V1\Admin\ApplicationController;
use App\Http\Controllers\V1\Admin\ApplicationCredentialsController;
use App\Http\Controllers\V1\Admin\ApplicationPermissionsController;
use App\Http\Controllers\V1\Admin\ApplicationTemplateController;
use App\Http\Controllers\V1\Admin\ApplicationTemplateVersionController;
use App\Http\Controllers\V1\User\DocumentGenerationController;
use App\Http\Controllers\V1\User\TemplateController;
use Illuminate\Support\Facades\Route;


/**
 * ADMIN ROUTES
 */
Route::prefix('admin')->group(function () {
	Route::apiResource('applications', ApplicationController::class);

	Route::prefix('applications/{application}')->group(function () {
		Route::apiResource('credentials', ApplicationCredentialsController::class);
		Route::apiResource('permissions', ApplicationPermissionsController::class);
		Route::apiResource('templates', ApplicationTemplateController::class);

		Route::prefix('templates/{template}')->group(function () {
			Route::apiResource('versions', ApplicationTemplateVersionController::class)
				->except('destroy');

			Route::post('versions/{version}/publish', [ApplicationTemplateVersionController::class, 'publish']);
			Route::post('versions/{version}/archive', [ApplicationTemplateVersionController::class, 'archive']);
		});

		Route::post('documents/generate', [AdminDocumentGenerationController::class, 'store']);
		Route::post('documents/{document}/download', [AdminDocumentGenerationController::class, 'download']);
	});
});


/**
 *  USER ROUTES
 */
Route::middleware('application.auth')->group(function () {
	Route::apiResource('templates', TemplateController::class);

	Route::prefix('templates/{template}')->group(function () {
		Route::apiResource('versions', ApplicationTemplateVersionController::class)
			->except('destroy');

		Route::post('versions/{version}/publish', [ApplicationTemplateVersionController::class, 'publish']);
		Route::post('versions/{version}/archive', [ApplicationTemplateVersionController::class, 'archive']);
	});

	Route::post('documents/generate', [DocumentGenerationController::class, 'store']);
	Route::post('documents/{document}/download', [DocumentGenerationController::class, 'download']);
});
