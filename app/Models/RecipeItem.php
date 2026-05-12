<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{

    protected $fillable = [

        'menu_item_id',
        'component_type',
        'component_code',
        'component_name',
        'quantity',
        'cost_rate',
        'component_cost'

    ];

    
}