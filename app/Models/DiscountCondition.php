<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DiscountMaster;

class DiscountCondition extends Model
{
    protected $table = 'discount_conditions';
    protected $primaryKey = 'condition_id';
    protected $fillable = [
        'condition_id',
        'discount_id',
        'condition_type',
        'operator',
        'value',
        'note'
    ];

    public function discount()
    {
        return $this->belongsTo(DiscountMaster::class, 'discount_id', 'discount_id');
    }
}