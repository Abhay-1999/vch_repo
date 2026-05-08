<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{

    protected $fillable = [

        'material_id',
        'type',
        'qty',
        'stock_before',
        'stock_after',
        'reference_no',
        'remarks'

    ];

    public function material()
    {

        return $this->belongsTo(
            RawMaterial::class,
            'material_id'
        );
    }
}