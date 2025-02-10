<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'mobile',
        'slug',
        'uuid',
        'avatar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
