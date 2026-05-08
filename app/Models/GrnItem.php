<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class GrnItem extends Model
{
    use SoftDeletes;
 
    protected $table = 'grn_items';
 
    protected $fillable = [
        'grn_header_id', 'grn_no',
        'material_code', 'material_name', 'batch_lot_no', 'mfg_date', 'expiry_date',
        'qty_purchase_uom', 'purchase_uom', 'conversion_factor', 'qty_base_uom', 'base_uom',
        'rate_per_purchase_uom', 'taxable_value', 'discount_percent', 'discount_amount',
        'net_taxable_value', 'gst_rate', 'cgst', 'sgst', 'igst', 'total_gst',
        'other_charges', 'round_off', 'total_amount', 'effective_cost_per_base_uom',
        'quality_check', 'accepted_qty_base_uom', 'rejected_qty_base_uom', 'rejection_reason',
        'payment_status', 'payment_date', 'payment_reference', 'remark',
    ];
 
    protected $casts = [
        'mfg_date'                   => 'date',
        'expiry_date'                => 'date',
        'payment_date'               => 'date',
        'qty_purchase_uom'           => 'decimal:4',
        'conversion_factor'          => 'decimal:4',
        'qty_base_uom'               => 'decimal:4',
        'rate_per_purchase_uom'      => 'decimal:2',
        'taxable_value'              => 'decimal:2',
        'discount_percent'           => 'decimal:2',
        'discount_amount'            => 'decimal:2',
        'net_taxable_value'          => 'decimal:2',
        'gst_rate'                   => 'decimal:2',
        'cgst'                       => 'decimal:2',
        'sgst'                       => 'decimal:2',
        'igst'                       => 'decimal:2',
        'total_gst'                  => 'decimal:2',
        'other_charges'              => 'decimal:2',
        'round_off'                  => 'decimal:2',
        'total_amount'               => 'decimal:2',
        'effective_cost_per_base_uom'=> 'decimal:4',
        'accepted_qty_base_uom'      => 'decimal:4',
        'rejected_qty_base_uom'      => 'decimal:4',
    ];
 
    public function header()
    {
        return $this->belongsTo(GrnHeader::class, 'grn_header_id');
    }
}