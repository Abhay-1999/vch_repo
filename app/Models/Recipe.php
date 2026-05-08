<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{

    protected $fillable = [
        'item_code'
    ];

    public function item()
    {
        return $this->belongsTo(
            ItemMaster::class,
            'item_code',
            'item_code'
        );
    }

    public function recipeItems()
    {
        return $this->hasMany(
            RecipeItem::class,
            'recipe_id'
        );
    }
}