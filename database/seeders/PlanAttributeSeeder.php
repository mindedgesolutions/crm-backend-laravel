<?php

namespace Database\Seeders;

use App\Models\PlanAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlanAttribute::create([
            'attribute' => $attr = 'Lead Management',
            'type' => 'text',
            'slug' => Str::slug($attr),
            'name' => 'lm_1'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Team Members',
            'type' => 'text',
            'slug' => Str::slug($attr),
            'name' => 'tm_2'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'WhatsApp Business API',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'wba_3'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Conversation Messages',
            'type' => 'text',
            'slug' => Str::slug($attr),
            'name' => 'cm_4'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Per Message Markup',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'pmm_5'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Schedule Messages',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'sm_6'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Integrations',
            'type' => 'text',
            'slug' => Str::slug($attr),
            'name' => 'i_7'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'SIM-based Call',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'sbc_8'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Automation 360',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'a3_9'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Meetings & Appointments',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'ma_10'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Reporting & Analytics',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'ra_11'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Quotes & Invoices',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'qi_12'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'API Integrations',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'ai_13'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'Live Support',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'ls_14'
        ]);
        PlanAttribute::create([
            'attribute' => $attr = 'AI Bot Reply',
            'type' => 'radio',
            'slug' => Str::slug($attr),
            'name' => 'abr_15'
        ]);
    }
}
