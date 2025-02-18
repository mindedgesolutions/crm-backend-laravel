<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserEncIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $faker = Faker::create();

        foreach ($users as $user) {
            // UserDetail::where('user_id', $user->id)->update([
            //     'user_id' => $user->id,
            //     'mobile' => '8335906101',
            //     'slug' => Str::slug($user->name),
            //     'uuid' => Str::uuid(),
            //     'enc_id' => Crypt::encrypt($user->id),
            // ]);

            UserDetail::insert([
                'user_id' => $user->id,
                'mobile' => $faker->phoneNumber,
                'slug' => Str::slug($user->name),
                'uuid' => Str::uuid(),
                'enc_id' => Crypt::encrypt($user->id),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
