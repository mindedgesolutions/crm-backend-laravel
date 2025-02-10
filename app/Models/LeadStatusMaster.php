<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatusMaster extends Model
{
    protected $table = 'lead_status_master';

    protected $fillable = ['company_id', 'name', 'slug', 'is_active'];
}
