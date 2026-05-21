<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DiscountMaster;

class DiscountCondition extends Model
{
    protected $table = 'discount_conditions';

    protected $fillable = [
        'condition_group_id',
        'discount_id',
        'condition_type',
        'operator',
        'value',
        'note',
        'active'
    ];


    public function discount()
    {
        return $this->belongsTo(DiscountMaster::class, 'discount_id', 'discount_id');
    }
}