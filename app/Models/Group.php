<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['company_id', 'name', 'slug', 'short_desc', 'group_img'];
}
