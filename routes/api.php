<?php

use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\PlanAttributesController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth:api'])->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->group(function () {
        Route::post('logout', 'logout');
        Route::get('current-user', 'currentUser');
    });

    Route::prefix('admin')->group(function () {
        Route::apiResource('plan-attributes', PlanAttributesController::class)->except('show');
        Route::apiResource('companies', CompanyController::class)->except(['destroy']);
    });
});
