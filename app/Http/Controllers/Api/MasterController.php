<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanAttribute;

class MasterController extends Controller
{
    public function planAttributes()
    {
        $attributes = PlanAttribute::select('id', 'attribute', 'name', 'type')
            ->where('is_active', true)
            ->orderBy('attribute')
            ->get();

        return response()->json($attributes);
    }
}
