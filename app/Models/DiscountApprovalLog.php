<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountApprovalLog extends Model
{
    use HasFactory;

    protected $table = 'discount_approval_logs';

    protected $fillable = [
        'rule_id',
        'user_id',
        'approval_role',
        'otp_code',
        'status',
        'approved_at'
    ];
}