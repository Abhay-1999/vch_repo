<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseEntryDt extends Model
{
    protected $table = 'raw_material_purchase_dt';

    protected $fillable = [
        'rest_cd','trans_no','trans_date','item_code','qty',
        'rate','unit_cd','sgst_per','cgst_per','sgst_amt','cgst_amt'
    ];
}
