<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Company;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('userDetail', function ($query) {
            $query->whereNull('company_id');
        })->orderBy('name')->paginate(10);

        return UserResource::collection($users);
    }

    // ---------------------------------------------------

    public function companyUsers()
    {
        $companies = Company::select('id', 'name', 'slug')->orderBy('name')->get();

        $companyId = $companies && request()->query('company')
            ? Arr::first($companies, fn($co) => $co['slug'] === request()->query('company'))->id
            : null;
        $role = request()->query('role') ?? null;
        $search = request()->query('search') ?? null;

        $cousers = User::join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('companies', 'user_details.company_id', '=', 'companies.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.*', 'companies.name as company_name')
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('companies.id', $companyId);
            })
            ->when($role, function ($query) use ($role) {
                $query->where('roles.name', $role);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('users.name', 'like', "%$search%")
                    ->orWhere('users.email', 'like', "%$search%")
                    ->orWhere('user_details.mobile', 'like', "%$search%");
            })
            ->orderBy('companies.name')
            ->orderBy('users.name')
            ->paginate(10);

        return UserResource::collection($cousers)->additional(['companies' => $companies]);
    }

    // ---------------------------------------------------

    public function store(AdminUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt('password'),
            ])->assignRole('super admin');

            UserDetail::insert([
                'user_id' => $user->id,
                'mobile' => $request->mobile,
                'slug' => Str::slug($request->name),
                'uuid' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'User created successfully!'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error("Something went wrong! " . $th->getMessage());
            DB::rollBack();
            return response()->json(['errors' => 'Something went wrong!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ---------------------------------------------------

    public function update(AdminUserRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            User::whereId($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => now(),
            ]);

            UserDetail::where('user_id', $id)->update([
                'slug' => Str::slug($request->name),
                'mobile' => $request->mobile,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json(['message' => 'User updated successfully!'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error("Something went wrong! " . $th->getMessage());
            DB::rollBack();
            return response()->json(['errors' => 'Something went wrong!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ---------------------------------------------------

    public function destroy(string $id)
    {
        UserDetail::where('user_id', $id)->update(['is_active' => false]);

        User::whereId($id)->update(['updated_at' => now()]);

        return response()->json(['message' => 'User deleted successfully!'], Response::HTTP_OK);
    }

    // ---------------------------------------------------

    public function activateUser(string $id)
    {
        UserDetail::where('user_id', $id)->update(['is_active' => true]);

        User::whereId($id)->update(['updated_at' => now()]);

        return response()->json(['message' => 'User activated successfully!'], Response::HTTP_OK);
    }
}
