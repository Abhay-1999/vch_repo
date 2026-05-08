<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialMaster extends Model
{
    use HasFactory;

    protected $table = 'raw_material_master';
    public $timestamps = false;
    protected $fillable = [

        // BASIC INFO
        'material_code',
        'material_name',
        'description',
        'category',
        'sub_category',
        'hsn_sac_code',

        // TAX & UOM
        'gst_rate',
        'base_uom',
        'purchase_uom',
        'conversion_factor',
        'recipe_uom',
        'recipe_conversion',

        // PRICING
        'standard_cost',
        'mrp',

        // STOCK
        'min_stock_level',
        'max_stock_level',
        'reorder_quantity',
        'lead_time_days',
        'shelf_life_days',
        'wastage_allowance',

        // STORAGE / SUPPLIER
        'storage_type',
        'storage_location',
        'primary_supplier_id',
        'alternate_supplier_id',

        // STATUS
        'perishable',
        'batch_tracked',
        'active',

        // DATES / EXTRA
        'created_on',
        'last_updated',
        'remarks'
    ];
}