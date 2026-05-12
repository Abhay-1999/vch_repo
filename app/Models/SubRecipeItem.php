<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubRecipeItem extends Model
{
    use HasFactory;

    protected $table = 'sub_recipe_items';

    protected $fillable = [

        'sub_recipe_id',
        'ingredient_id',
        'quantity_used',
        'costing_rate',
        'line_cost'

    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function subRecipe()
    {
        return $this->belongsTo(SubRecipe::class);
    }
}