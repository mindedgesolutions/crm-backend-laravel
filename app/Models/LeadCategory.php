<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadCategory extends Model
{
    protected $fillable = ['company_id', 'name', 'slug', 'lead_cat_img', 'is_active'];
}
