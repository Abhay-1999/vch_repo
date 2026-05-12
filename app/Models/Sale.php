<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    protected $fillable = [

        'sale_date',
        'menu_item_id',

        'qty_sold',
        'selling_price',
        'plate_cost',

        'sales_value',
        'cogs',
        'gross_margin',
        'food_cost_percent',
    ];
}