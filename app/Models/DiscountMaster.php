<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountMaster extends Model
{
    use HasFactory;

    protected $table = 'discount_master';
    public $timestamps = false;

    protected $fillable = [

        'discount_id',
        'name',
        'type',
        'value',
        'unit',
        'max_cap',
        'min_bill',
        'applies_to',
        'stackable',
        'auto_apply',
        'approval_req',
        'approval_level',
        'valid_from',
        'valid_to',
        'active_days',
        'active_hours',
        'channel',
        'outlet',
        'status',
        'created_by',
        'created_on',
        'remarks',
    ];
}