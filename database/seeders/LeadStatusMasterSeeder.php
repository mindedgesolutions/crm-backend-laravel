<?php

namespace Database\Seeders;

use App\Models\LeadStatusMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LeadStatusMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = ['New', 'Contacted', 'Interested', 'Qualified', 'Won/Closed', 'Lost/Closed', 'Follow-up Required', 'Duplicate', 'On-hold', 'Other'];

        foreach ($status as $name) {
            LeadStatusMaster::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
