<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{

    protected $fillable = [

        'recipe_id',
        'material_id',
        'qty'

    ];

    public function material()
    {
        return $this->belongsTo(
            RawMaterial::class,
            'material_id'
        );
    }
}