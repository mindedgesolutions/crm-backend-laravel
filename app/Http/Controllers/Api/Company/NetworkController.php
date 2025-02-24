<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use App\Models\Network;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NetworkController extends Controller
{
    public function index()
    {
        $networks = Network::where('company_id', Auth::user()->userDetail->company_id)
            ->paginate(10);

        return response()->json($networks, Response::HTTP_OK);
    }

    // ------------------------------------------------------

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'min:3', function ($attribute, $value, $fail) {
                $slug = Str::slug($value);
                $exists = Network::whereSlug($slug)->where('company_id', Auth::user()->userDetail->company_id)->exists();
                if ($exists) {
                    $fail('Network exists.');
                }
            }],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:100',
        ], [
            'name.required' => 'Network name is required.',
            'name.max' => 'Name should not be greater than 255 characters.',
            'name.min' => 'Name should not be less than 3 characters.',
            'logo.image' => 'Logo should be an image.',
            'logo.mimes' => 'Logo should be of type jpeg, png, jpg.',
            'logo.max' => 'Logo should not be greater than 100 KB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $network = Network::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'company_id' => Auth::user()->userDetail->company_id,
            ]);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                $directory = 'uploads/networks';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                $filePath = $file->storeAs($directory, $filename, 'public');

                $network->update(['network_img' => Storage::url($filePath)]);
            }

            DB::commit();

            return response()->json('Created', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255', 'min:3', function ($attribute, $value, $fail) use ($id) {
                $slug = Str::slug($value);
                $exists = Network::whereSlug($slug)
                    ->where('company_id', Auth::user()->userDetail->company_id)
                    ->where('id', '!=', $id)
                    ->exists();

                if ($exists) {
                    $fail('Network exists.');
                }
            }],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:100',
        ], [
            'name.required' => 'Network name is required.',
            'name.max' => 'Name should not be greater than 255 characters.',
            'name.min' => 'Name should not be less than 3 characters.',
            'logo.image' => 'Logo should be an image.',
            'logo.mimes' => 'Logo should be of type jpeg, png, jpg.',
            'logo.max' => 'Logo should not be greater than 100 KB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $network = Network::findOrFail($id);

            $network->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ]);

            if ($request->hasFile('logo')) {
                if ($network->network_img) {
                    $relativePath = str_replace('/storage/', '', $network->network_img);
                    Storage::disk('public')->delete($relativePath);
                }

                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $filename = Str::random(10) . time() . '-' . $file->getClientOriginalName();
                $directory = 'uploads/networks';

                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                $filePath = $file->storeAs($directory, $filename, 'public');

                $network->update(['network_img' => Storage::url($filePath)]);
            }

            DB::commit();

            return response()->json('Created', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ------------------------------------------------------

    public function destroy(string $id)
    {
        Network::whereId($id)->update(['is_active' => false]);

        return response()->json('Deleted', Response::HTTP_OK);
    }

    // ------------------------------------------------------

    public function activate(string $id)
    {
        Network::whereId($id)->update(['is_active' => true]);

        return response()->json('Activated', Response::HTTP_OK);
    }
}
