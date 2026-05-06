<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierMaster extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_masters';

    protected $fillable = [
        // Basic
        'supp_name', 'supp_code', 'supp_type', 'gst_no',

        // Food Supply
        'supply_category', 'fssai_no', 'fssai_expiry',
        'delivery_days', 'delivery_slot',
        'min_order_value', 'lead_time_days', 'items_supplied',
        'quality_grade', 'is_organic',

        // Address
        'supp_add1', 'supp_add2', 'market_name',
        'city', 'state', 'pincode', 'country',

        // Contact
        'contact_person', 'contact_no', 'alt_contact_no',
        'whatsapp_no', 'email', 'pan_no',

        // Financial
        'opening_balance', 'credit_limit', 'payment_terms',
        'payment_mode', 'discount_pct',

        // Bank
        'bank_name', 'account_no', 'ifsc', 'upi_id',

        // Additional
        'status', 'supplier_rating', 'remark',
    ];

    protected $casts = [
        'fssai_expiry'    => 'date',
        'opening_balance' => 'decimal:2',
        'credit_limit'    => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'discount_pct'    => 'decimal:2',
        'lead_time_days'  => 'integer',
        'payment_terms'   => 'integer',
        'supplier_rating' => 'integer',
        'deleted_at'      => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('supply_category', $category);
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->supp_add1,
            $this->supp_add2,
            $this->city,
            $this->state,
            $this->pincode,
        ])->filter()->implode(', ');
    }

    // ── Auto-generate supplier code before creating ───────────

    protected static function booted(): void
    {
        static::creating(function (SupplierMaster $supplier) {
            if (empty($supplier->supp_code)) {
                $prefix = 'SUP';
                $last   = static::withTrashed()->max('id') ?? 0;
                $supplier->supp_code = $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}