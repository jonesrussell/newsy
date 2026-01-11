<?php

use App\Http\Controllers\Api\V1\MunicipalityController;
use App\Http\Controllers\Api\V1\MunicipalityNewsSourceController;
use App\Http\Controllers\Api\V1\NewsSourceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('municipalities', MunicipalityController::class)->only(['index', 'show']);
    Route::apiResource('municipalities.news-sources', MunicipalityNewsSourceController::class)->only(['index', 'store', 'destroy']);

    Route::apiResource('news-sources', NewsSourceController::class);
});
