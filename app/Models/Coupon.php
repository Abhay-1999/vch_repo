<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'coupon_code',
        'linked_discount_code',
        'discount_name',
        'usage_limit',
        'used_count',
        'once_per_customer',
        'valid_from',
        'valid_to',
        'min_bill',
        'status',
        'channel',
    ];

    // Remaining accessor
    public function getRemainingAttribute()
    {
        return $this->usage_limit - $this->used_count;
    }
}