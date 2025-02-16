<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'slug', 'short_desc', 'tenure', 'price', 'is_active', 'enc_id'];

    public function planAttribute()
    {
        return $this->belongsToMany(PlanAttribute::class, 'plan_attribute_mapping', 'plan_id', 'attr_id')
            ->withPivot(['attr_value']);
    }
}
