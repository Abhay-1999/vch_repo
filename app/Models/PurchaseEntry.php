<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseEntry extends Model
{
    protected $table = 'raw_material_purchase'; // यदि table का नाम अलग है तो बदलें

    protected $fillable = [
        'rest_cd','trans_no','trans_date','supp_cd','supp_billno',
        'supp_billdt','porder_no','porder_date','delvery_challn','delivery_date',
        'invoice_val',
        'r_off','gross_val'
    ];
}
