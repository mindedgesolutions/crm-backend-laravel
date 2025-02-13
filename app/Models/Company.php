<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'address',
        'location',
        'pincode',
        'city',
        'state',
        'email',
        'phone',
        'whatsapp',
        'contact_person',
        'slug',
        'uuid',
        'is_active',
        'website',
        'enc_id',
    ];

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'company_id', 'id');
    }
}
