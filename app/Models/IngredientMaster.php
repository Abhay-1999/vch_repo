<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientMaster extends Model
{
    protected $table = 'ingredient_masters';
    public $timestamps = false;


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
        'current_stock',
        'min_stock',
        'remarks',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];
}