<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHead extends Model
{
    protected $fillable = [
        'purchase_date',
        'supplier_id',
        'grand_total'
    ];

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class,'purchase_id');
    }
}
