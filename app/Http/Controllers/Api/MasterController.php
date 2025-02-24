<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeadStatusMaster;
use App\Models\Network;
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

    // -------------------------------------------------------

    public function leadStatus($companyId)
    {
        $status = LeadStatusMaster::where('company_id', $companyId)
            ->orWhereNull('company_id')
            ->get();

        return response()->json($status);
    }

    // -------------------------------------------------------

    public function networks($companyId)
    {
        $networks = Network::where('company_id', $companyId)->get();

        return response()->json($networks);
    }
}
