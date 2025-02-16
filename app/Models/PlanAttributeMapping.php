<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAttributeMapping extends Model
{
    protected $table = 'plan_attribute_mapping';

    protected $fillable = [
        'plan_id',
        'attr_id',
        'attr_value',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function attribute()
    {
        return $this->belongsTo(PlanAttribute::class, 'attr_id');
    }
}
