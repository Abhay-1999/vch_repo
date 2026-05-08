<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeMapping extends Model
{
    protected $table = 'recipe_mappings';
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'item_code',
        'item_name',
        'selling_price',
        'standard_yield',
        'material_code',
        'material_name',
        'qty_per_serving',
        'recipe_uom',
        'qty_in_base_uom',
        'cost_per_base_uom',
        'ingredient_cost',
        'wastage_allowance',
        'effective_cost',
        'active',
        'effective_from',
        'effective_to',
        'created_by',
        'approved_by',
        'remarks',
    ];

  
}