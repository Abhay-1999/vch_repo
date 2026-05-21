<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTier extends Model
{
    protected $fillable = [
        'tier_id',
        'tier_name',
        'min_lifetime_spend',
        'max_lifetime_spend',
        'auto_discount_percent',
        'discount_cap',
        'birthday_bonus_percent',
        'reward_points_per_100',
        'linked_discount_code',
        'color',
        'status',
        'remarks',
    ];
}