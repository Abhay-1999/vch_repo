<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'item_code',
        'item_name',
        'category',
        'servings_per_recipe',
        'plate_cost',
        'target_food_cost_percent',
        'suggested_price',
        'actual_price',
        'gst_rate',
        'price_including_gst',
        'rounded_price',
        'contribution_margin'
    ];

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }
}