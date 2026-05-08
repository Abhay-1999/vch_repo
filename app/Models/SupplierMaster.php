<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierMaster extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_masters';

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Basic Details
        |--------------------------------------------------------------------------
        */

        'supplier_id',
        'supplier_name',
        'trade_brand_name',
        'category',

        /*
        |--------------------------------------------------------------------------
        | Contact Information
        |--------------------------------------------------------------------------
        */

        'contact_person',
        'designation',
        'mobile_no',
        'alt_phone',
        'email_id',

        /*
        |--------------------------------------------------------------------------
        | Address Information
        |--------------------------------------------------------------------------
        */

        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'country',

        /*
        |--------------------------------------------------------------------------
        | Tax & Legal Information
        |--------------------------------------------------------------------------
        */

        'gstin',
        'pan',
        'fssai_license_no',
        'fssai_expiry',
        'msme_udyam_no',

        /*
        |--------------------------------------------------------------------------
        | Bank Details
        |--------------------------------------------------------------------------
        */

        'bank_name',
        'bank_account_no',
        'ifsc_code',
        'account_holder_name',

        /*
        |--------------------------------------------------------------------------
        | Payment Settings
        |--------------------------------------------------------------------------
        */

        'payment_terms',
        'credit_limit',
        'currency',
        'tds_applicable',
        'tds_rate',
        'lead_time_days',

        /*
        |--------------------------------------------------------------------------
        | Status & Audit
        |--------------------------------------------------------------------------
        */

        'rating',
        'status',
        'onboarded_on',
        'onboarded_by',
        'remarks',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'credit_limit' => 'decimal:2',

        'tds_rate' => 'decimal:2',

        'lead_time_days' => 'integer',

        'rating' => 'integer',

        'fssai_expiry' => 'date',

        'onboarded_on' => 'date',

        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Active Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /*
    |--------------------------------------------------------------------------
    | Category Scope
    |--------------------------------------------------------------------------
    */

    public function scopeByCategory(
        $query,
        string $category
    ) {

        return $query->where(
            'category',
            $category
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Full Address Accessor
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute(): string
    {
        return collect([

            $this->address_line1,

            $this->address_line2,

            $this->city,

            $this->state,

            $this->pincode,

            $this->country,

        ])

        ->filter()

        ->implode(', ');
    }

    /*
    |--------------------------------------------------------------------------
    | Auto Supplier ID Generate
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (
            SupplierMaster $supplier
        ) {

            if (empty($supplier->supplier_id)) {

                $prefix = 'SUP';

                $lastId = static::withTrashed()
                    ->max('id') ?? 0;

                $supplier->supplier_id =
                    $prefix .
                    str_pad(
                        $lastId + 1,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );
            }
        });
    }
}