<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PlanAttributesController extends Controller
{
    public function index(Request $request)
    {
        $attributes = PlanAttribute::orderBy('attribute')->paginate(10);

        return response()->json(['attributes' => $attributes], Response::HTTP_OK);
    }

    // ------------------------------------------------------------------------

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute' => 'required|unique:plan_attributes,attribute',
            'type' => 'required',
        ], [
            '*.required' => ':Attribute is required',
            'attribute.unique' => 'Attribute already exists',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        if ($request->type != 'text' && $request->type != 'radio') {
            return response()->json(['errors' => ['type' => 'Type must be text or radio']], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $attr = PlanAttribute::create([
                'attribute' => $request->attribute,
                'type' => $request->type,
                'slug' => Str::slug($request->attribute),
            ]);

            $fl = getFirstLetters(trim($request->attribute));
            $name = $fl . '_' . $attr->id;

            PlanAttribute::where('id', $attr->id)->update(['name' => $name]);

            DB::commit();

            return response()->json(['message' => 'Attribute created successfully'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Something went wrong! ' . $th->getMessage());
            return response()->json(['errors' => 'Something went wrong!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ------------------------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'attribute' => 'required|unique:plan_attributes,attribute,' . $id,
            'type' => 'required',
        ], [
            '*.required' => ':Attribute is required',
            'attribute.unique' => ':Attribute already exists',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        if ($request->type != 'text' && $request->type != 'radio') {
            return response()->json(['errors' => ['type' => 'Type must be text or radio']], Response::HTTP_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $attr = PlanAttribute::whereId($id)->update([
                'attribute' => $request->attribute,
                'type' => $request->type,
                'slug' => Str::slug($request->attribute),
            ]);

            $fl = getFirstLetters(trim($request->attribute));
            $name = $fl . '_' . $id;

            PlanAttribute::where('id', $id)->update(['name' => $name]);

            DB::commit();

            return response()->json(['message' => 'Attribute updated successfully'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Something went wrong! ' . $th->getMessage());
            return response()->json(['errors' => 'Something went wrong!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ------------------------------------------------------------------------

    public function destroy(string $id)
    {
        PlanAttribute::whereId($id)->delete();

        return response()->json(['message' => 'Attribute deleted successfully'], Response::HTTP_OK);
    }
}
