<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{

    protected $fillable = [
        'material_id',
        'type',
        'reference_no',
        'qty',
        'stock_before',
        'stock_after'
    ];

  
}