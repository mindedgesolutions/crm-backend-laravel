<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Models\LeadStatusMaster;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LeadStatusController extends Controller
{
    public function index()
    {
        $status = LeadStatusMaster::where('company_id', Auth::user()->userDetail->company_id)
            ->orWhereNull('company_id')
            ->paginate(10);

        return response()->json($status);
    }

    // ------------------------------------------------------

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'min:3', function ($attribute, $value, $fail) {
                $slug = Str::slug($value);
                $check = LeadStatusMaster::whereSlug($slug)->first();
                if ($check) {
                    $fail('Lead status already exists');
                }
            }],
        ], [
            '*.required' => ':Attribute is required',
            'name.max' => 'Maximum 255 characters allowed',
            'name.min' => 'Minimum 3 characters required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        LeadStatusMaster::create([
            'company_id' => Auth::user()->userDetail->company_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => true
        ]);

        return response()->json(['message' => 'Lead status added successfully'], Response::HTTP_CREATED);
    }

    // ------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'min:3', function ($attribute, $value, $fail) use ($id) {
                $slug = Str::slug($value);
                $check = LeadStatusMaster::whereSlug($slug)->where('id', '!=', $id)->first();
                if ($check) {
                    $fail('Lead status already exists');
                }
            }],
            [
                '*.required' => ':Attribute is required',
                'name.max' => 'Maximum 255 characters allowed',
                'name.min' => 'Minimum 3 characters required'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        LeadStatusMaster::whereId($id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Lead status updated successfully'], Response::HTTP_OK);
    }

    // ------------------------------------------------------

    public function destroy(string $id)
    {
        LeadStatusMaster::whereId($id)->update(['is_active' => false]);

        return response()->json(['message' => 'Lead status deleted successfully'], Response::HTTP_OK);
    }

    // ------------------------------------------------------

    public function activate($id)
    {
        LeadStatusMaster::whereId($id)->update(['is_active' => true]);

        return response()->json(['message' => 'Lead status activated successfully'], Response::HTTP_OK);
    }
}
