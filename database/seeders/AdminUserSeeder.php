<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userOne = User::create([
            'name' => 'Souvik Nag',
            'email' => 'souvik@test.com',
            'password' => bcrypt('password'),
        ])->assignRole('super admin');

        User::create([
            'name' => 'Rakesh Bairagi',
            'email' => 'rakesh@test.com',
            'password' => bcrypt('password'),
        ])->assignRole('super admin');
    }
}
