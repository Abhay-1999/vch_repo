<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RawMaterialMaster extends Model
{
    protected $table = "raw_material_master";
    protected $fillable = ['rest_cd','item_desc', 'qty', 'unit_cd', 'remark','supp_cd', 'supp_billno', 'supp_billdt', 'catg_cd'];
}
