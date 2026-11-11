<?php

use App\Http\Controllers\ApplicationPermissionsController;
use App\Http\Controllers\V1\Admin\ApplicationController;
use App\Http\Controllers\V1\Admin\ApplicationCredentialsController;
use App\Http\Controllers\V1\Admin\ApplicationTemplateController;
use Illuminate\Support\Facades\Route;


/**
 * ADMIN ROUTES
 */
Route::prefix('admin')->group(function () {
	Route::apiResource('applications', ApplicationController::class);
	Route::apiResource('applications/{application}/credentials', ApplicationCredentialsController::class);
	Route::apiResource('applications/{application}/permissions', ApplicationPermissionsController::class);
	Route::apiResource('applications/{application}/templates', ApplicationTemplateController::class);
});


/**
 *  USER ROUTES
 */
//Route::middleware('application.auth')->group(function () {
//	Route::post('documents/generate', [DocumentGenerationController::class, 'store']);
//	Route::post('documents/download', [DocumentGenerationController::class, 'download']);
//	Route::apiResource('templates', ApplicationTemplateController::class);
//});
