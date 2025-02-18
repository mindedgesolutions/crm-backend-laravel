<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function superAdminDashboard()
    {
        return response()->json(['message' => 'Super Admin Dashboard'], Response::HTTP_OK);
    }

    public function adminDashboard() {}

    public function managerDashboard() {}

    public function employeeDashboard() {}
}
