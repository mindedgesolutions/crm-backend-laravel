<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable = ['name', 'slug', 'company_id', 'network_img', 'is_active'];
}
