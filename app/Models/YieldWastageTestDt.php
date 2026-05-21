<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YieldWastageTestDt extends Model
{
    protected $table = 'yield_wastage_test_dt';

    protected $fillable = [
        'test_hd_id',
        'ingredient_code',
        'ingredient_name',
        'ap_weight',
        'trim_loss',
        'cooking_loss',
        'ep_weight',
        'yield_percent',
        'ap_cost',
        'ap_cost_per_gm',
        'ep_cost_per_gm'
    ];
}