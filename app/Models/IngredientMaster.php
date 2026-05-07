<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientMaster extends Model
{
   protected $table = 'ingredient_master';
    protected $fillable = [
        'ingredient_name',
        'unit',
        'rate' 
    ];

    public $timestamps = false;
}
