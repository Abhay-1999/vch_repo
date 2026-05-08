<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = [
        'purchase_id',
        'material_id',
        'qty',
        'rate',
        'amount'
    ];

    public function material()
    {
        return $this->belongsTo(RawMaterial::class,'material_id');
    }
}