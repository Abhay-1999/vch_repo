<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\IngredientMaster;
class ItemIngredient extends Model
{
    protected $table = 'item_ingredients';

    protected $fillable = [
        'item_code',
        'ingredient_id',
        'qty',
        'unit'
    ];

        public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }

    public function ingredient()
    {
        return $this->belongsTo(IngredientMaster::class, 'ingredient_id');
    }
    public $timestamps = false;
}