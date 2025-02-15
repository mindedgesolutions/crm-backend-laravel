<?php

use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\PlanAttributesController;
use App\Http\Controllers\Api\Admin\PlanController;
use App\Http\Controllers\Api\Admin\UserController;
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
        Route::post('update-profile', 'updateProfile');
    });

    Route::middleware('role:super admin')->prefix('admin')->group(function () {
        Route::apiResource('companies', CompanyController::class)->except(['destroy']);
        Route::apiResource('plan-attributes', PlanAttributesController::class)->except('show');
        Route::apiResource('plans', PlanController::class);
        Route::apiResource('users', UserController::class)->except(['show']);
        Route::get('company-users', [UserController::class, 'companyUsers']);
        Route::put('activate-users/{user}', [UserController::class, 'activateUser']);
    });
});
