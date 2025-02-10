<?php

use App\Http\Controllers\Api\Admin\PlanAttributesController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('plan-attributes', PlanAttributesController::class)->except('show');
});
