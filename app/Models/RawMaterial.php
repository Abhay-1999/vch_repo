<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'material_name',
        'material_code',
        'unit_id',
        'opening_stock',
        'current_stock',
        'min_stock_alert',
        'purchase_rate'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
