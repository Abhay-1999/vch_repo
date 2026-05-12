<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubRecipe extends Model
{
    protected $fillable = [
        'sub_recipe_code',
        'sub_recipe_name',
        'batch_output',
        'total_cost',
        'cost_per_gram'
    ];

    public function items()
    {
        return $this->hasMany(SubRecipeItem::class);
    }
}