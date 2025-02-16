<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanAttribute;
use App\Models\PlanAttributeMapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('planAttribute')->orderBy('name')->get();

        return response()->json(['plans' => $plans]);
    }

    // ------------------------------------------------------------------------

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'shortDesc' => 'required',
            'price' => 'required|numeric',
        ], [
            '*.required' => ':Attribute is required',
            '*.string' => ':Attribute must be a string',
            '*.numeric' => ':Attribute must be a number',
        ], [
            'name' => 'name',
            'shortDesc' => 'short description',
            'price' => 'price',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->name) . '-' . $request->price;

            $plan = Plan::create([
                'name' => $request->name,
                'slug' => $slug,
                'short_desc' => $request->shortDesc,
                'tenure' => $request->tenure,
                'price' => $request->price
            ]);

            $plan->update([
                'enc_id' => Crypt::encrypt($request->name),
            ]);

            foreach (json_decode($request->input('attributes'), true) as $key => $attr) {
                $attrId = PlanAttribute::where('name', $key)->first()->id;

                PlanAttributeMapping::insert([
                    'plan_id' => $plan->id,
                    'attr_id' => $attrId,
                    'attr_value' => $attr,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Plan created successfully'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Something went wrong: ' . $th->getMessage());
            DB::rollBack();
            return response()->json(['errors' => ['name' => 'Something went wrong']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $slug = Str::slug($request->name);

        $exists = Plan::where('slug', $slug)->exists();

        if ($exists) {
            return response()->json(['errors' => ['name' => 'Plan already exists']], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
