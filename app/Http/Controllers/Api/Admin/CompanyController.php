<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Models\Company;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index()
    {
        //
    }

    public function store(CompanyRequest $request)
    {
        try {
            DB::beginTransaction();

            $company = Company::create([
                'name' => trim($request->name),
                'address' => trim($request->address),
                'location' => trim($request->location),
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'email' => $request->email,
                'phone' => $request->mobile,
                'whatsapp' => $request->whatsapp ?? $request->mobile,
                'contact_person' => $request->contactPerson,
                'slug' => Str::slug($request->name),
                'uuid' => Str::uuid(),
                'website' => $request->website,
            ]);

            $user = User::create([
                'name' => $request->contactPerson,
                'email' => $request->userEmail,
                'password' => bcrypt('password'),
            ]);

            UserDetail::insert([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'mobile' => $request->mobile,
                'slug' => Str::slug($request->contactPerson),
                'uuid' => Str::uuid(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('An error occurred: ' . $th->getMessage());
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
