<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountApprovalRule extends Model
{
    protected $fillable = [

        'rule_id',
        'condition',
        'threshold',
        'approval_required',
        'otp_password',
        'audit_log',
        'email_alert_to',
        'remarks',
        'status'
    ];
}