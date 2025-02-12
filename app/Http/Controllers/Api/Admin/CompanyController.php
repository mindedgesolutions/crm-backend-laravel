<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Http\Resources\Admin\CompanyResource;
use App\Models\Company;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('userDetail', 'userDetail.user')->orderBy('name')->paginate(10);

        return CompanyResource::collection($companies);
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
                'whatsapp' => $request->whatsapp,
                'contact_person' => $request->contactPerson,
                'slug' => Str::slug($request->name),
                'uuid' => Str::uuid(),
                'website' => $request->website,
            ]);

            Company::whereId($company->id)->update([
                'enc_id' => Crypt::encrypt($company->id),
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
            return response()->json(['errors' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $decId = Crypt::decrypt($id);

        $company = Company::with('userDetail', 'userDetail.user')->findOrFail($decId);

        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, string $id)
    {
        $decId = Crypt::decrypt($id);

        try {
            DB::beginTransaction();

            Company::whereId($decId)->update([
                'name' => trim($request->name),
                'address' => trim($request->address),
                'location' => trim($request->location),
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'email' => $request->email,
                'phone' => $request->mobile,
                'whatsapp' => $request->whatsapp,
                'contact_person' => $request->contactPerson,
                'slug' => Str::slug($request->name),
                'website' => $request->website,
            ]);

            $userId = UserDetail::where('company_id', $decId)->first()->user_id;

            User::where('id', $userId)->update([
                'name' => $request->contactPerson,
                'email' => $request->userEmail,
            ]);

            UserDetail::where('user_id', $userId)->update([
                'mobile' => $request->mobile,
                'slug' => Str::slug($request->contactPerson),
            ]);

            DB::commit();

            return response()->json(['message' => 'Success'], Response::HTTP_ACCEPTED);
        } catch (\Throwable $th) {
            Log::error('An error occurred: ' . $th->getMessage());
            DB::rollBack();
            return response()->json(['errors' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
