<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredient_masters';

    protected $fillable = [

        'ingredient_code',
        'ingredient_name',
        'category',

        'purchase_uom',
        'purchase_qty',
        'purchase_cost',
        'cost_per_purchase_unit',

        'base_uom',
        'conversion_to_base',
        'gross_cost_per_base_unit',

        'yield_percent',
        'net_cost_per_base_unit',
        'wastage_allowance_percent',
        'costing_rate',

        'supplier',
        'last_updated',
        'remarks',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];
}