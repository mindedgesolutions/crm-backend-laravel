<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'company_id',
        'added_by',
        'added_at',
        'name',
        'mobile',
        'whatsapp',
        'email',
        'address',
        'city',
        'state',
        'other_info',
        'uuid',
        'network_id',
        'lead_category_id',
        'is_active',
    ];
}
