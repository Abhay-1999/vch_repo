<?php
// ═══════════════════════════════════════════════════════════════
// FILE 1: app/Models/GrnHeader.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class GrnHeader extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $table = 'grn_headers';
 
    protected $fillable = [
        'grn_no', 'grn_date', 'po_no', 'invoice_no', 'invoice_date',
        'supplier_id', 'supplier_name',
        'storage_location', 'received_by', 'verified_by',
        'payment_status', 'payment_date', 'payment_reference', 'remark',
        // aggregated
        'total_taxable_value', 'total_gst_amount', 'total_other_charges',
        'grand_total', 'item_count',
    ];
 
    protected $casts = [
        'grn_date'            => 'date',
        'invoice_date'        => 'date',
        'payment_date'        => 'date',
        'total_taxable_value' => 'decimal:2',
        'total_gst_amount'    => 'decimal:2',
        'total_other_charges' => 'decimal:2',
        'grand_total'         => 'decimal:2',
    ];
 
    public function items()
    {
        return $this->hasMany(GrnItem::class, 'grn_header_id');
    }
}