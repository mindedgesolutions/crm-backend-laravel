<?php

use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\PlanAttributesController;
use App\Http\Controllers\Api\Admin\PlanController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Company\LeadStatusController;
use App\Http\Controllers\Api\Company\NetworkController;
use App\Http\Controllers\Api\Company\UserController as CompanyUserController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MasterController;
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
        Route::get('super/dashboard', [DashboardController::class, 'superAdminDashboard']);
        Route::apiResource('companies', CompanyController::class)->except(['destroy']);
        Route::apiResource('users', UserController::class)->except(['show']);
        Route::get('company-users', [UserController::class, 'companyUsers']);
        Route::put('activate-users/{user}', [UserController::class, 'activateUser']);
        Route::apiResource('plan-attributes', PlanAttributesController::class)->except('show');
        Route::apiResource('plans', PlanController::class);
    });

    // For all dropdowns and pre-filled data start ---------------------
    Route::controller(MasterController::class)->prefix('masters')->group(function () {
        Route::get('plan-attributes', 'planAttributes');
        Route::get('lead-status/{companyId?}', 'leadStatus');
        Route::get('networks/{companyId?}', 'networks');
    });
    // For all dropdowns and pre-filled data end ---------------------

    Route::middleware(['role:admin'])->prefix('company')->group(function () {
        Route::apiResource('lead-status', LeadStatusController::class)->except(['show']);
        Route::put('lead-status-activate/{id}', [LeadStatusController::class, 'activate']);
        Route::apiResource('networks', NetworkController::class)->except(['show', 'update']);
        Route::post('network/update/{id}', [NetworkController::class, 'updateInfo']);
        Route::put('network-activate/{id}', [NetworkController::class, 'activate']);
        Route::apiResource('users', CompanyUserController::class);
    });
});
