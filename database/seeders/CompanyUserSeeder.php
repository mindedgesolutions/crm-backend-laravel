<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $companies = Company::all();

        foreach ($companies as $company) {
            $roles = ['admin', 'manager', 'manager', 'employee', 'employee', 'employee', 'employee', 'employee', 'employee', 'employee'];

            foreach ($roles as $role) {
                $user = User::create([
                    'name' => $name = $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => bcrypt('password'),
                ])->assignRole(Role::where('name', $role)->where('guard_name', 'web')->first());

                UserDetail::insert([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'mobile' => $faker->phoneNumber,
                    'slug' => Str::slug($name),
                    'uuid' => Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
