<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            $company = Company::create([
                'name' => $name = $faker->company,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'whatsapp' => $faker->phoneNumber,
                'contact_person' => $faker->name,
                'website' => $faker->url,
                'slug' => Str::slug($name),
                'uuid' => Str::uuid(),
            ]);

            Company::whereId($company->id)->update([
                'enc_id' => Crypt::encrypt($company->id),
            ]);
        }
    }
}
