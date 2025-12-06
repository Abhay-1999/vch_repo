<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierMaster extends Model
{
    protected $table = "supplier_master";
    protected $fillable = ['rest_cd','supp_name','supp_add1','supp_add2','city','gst_no','contact_person','contact_no','remark'];
}