<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAttribute extends Model
{
    protected $fillable = ['attribute', 'slug', 'name', 'type', 'is_active'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_attribute_mapping', 'attr_id', 'plan_id')
            ->withPivot(['attr_value']);
    }
}
