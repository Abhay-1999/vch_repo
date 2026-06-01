<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountApprovalRule;
use Illuminate\Support\Facades\DB;
use App\Models\DiscountApprovalLog;
class DiscountApprovalRuleController extends Controller
{

 public function index()
{
    $rules = DB::table('discount_approval_rules as r')
        ->leftJoin(
            DB::raw('(
                SELECT rule_id, status, mobile_no, approved_at
                FROM discount_approval_logs
                WHERE id IN (
                    SELECT MAX(id)
                    FROM discount_approval_logs
                    GROUP BY rule_id
                )
            ) as l'),
            'r.rule_id',
            '=',
            'l.rule_id'
        )
        ->select(
            'r.*',
            'l.status as approval_status',
            'l.mobile_no',
            'l.approved_at'
        )
        ->orderByDesc('r.id')
        ->get();

    return view('discount_rules.index', compact('rules'));
}

    public function create()
    {
        $conditions = DB::table('discount_conditions')
            ->select('condition_id', 'condition_type')
            ->distinct()
            ->get();
            

        return view('discount_rules.create', compact('conditions'));
    }


    public function store(Request $request)
    {
        $last = DiscountApprovalRule::latest()->first();

        if ($last) {
            $lastNumber = (int) substr($last->rule_id, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $ruleId = 'APR-' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        $otpAdminId = null;

        if ($request->otp_password == 'ADMIN_OTP') {
            $otpAdminId = '3';
        } elseif ($request->otp_password == 'MANAGER_OTP') {
            $otpAdminId = '4';
        } elseif ($request->otp_password == 'BOTH_OTP') {
            $otpAdminId = '3,4';
        }

        $rule = DiscountApprovalRule::create([
            'rule_id' => $ruleId,
            'condition' => $request->condition_id,
            'threshold' => $request->threshold,
            'approval_required' => $request->approval_required,
            'otp_password' => $request->otp_password,
            'otp_admin_id' => $otpAdminId,
            'audit_log' => $request->audit_log,
            'email_alert_to' => $request->email_alert_to,
            'remarks' => $request->remarks,
            'status' => 1,
        ]);

        // Create approval log records
        if ($request->otp_password == 'ADMIN_OTP') {

            DiscountApprovalLog::create([
                'rule_id' => $ruleId,
                'user_id' => 3,
                'approval_role' => 'ADMIN',
                'otp_code' => null,
                'mobile_no' => null,
                'status' => 'PENDING'
            ]);

        } elseif ($request->otp_password == 'MANAGER_OTP') {

            DiscountApprovalLog::create([
                'rule_id' => $ruleId,
                'user_id' => 4,
                'approval_role' => 'MANAGER',
                'otp_code' => null,
                'mobile_no' => null,
                'status' => 'PENDING'
            ]);

        } elseif ($request->otp_password == 'BOTH_OTP') {

            // ADMIN
                DiscountApprovalLog::create([
                    'rule_id' => $ruleId,
                    'user_id' => 3,
                    'approval_role' => 'ADMIN',
                    'otp_code' => null,
                    'mobile_no' => null,
                    'status' => 'PENDING'
                ]);

                // MANAGER
                DiscountApprovalLog::create([
                    'rule_id' => $ruleId,
                    'user_id' => 4,
                    'approval_role' => 'MANAGER',
                    'otp_code' => null,
                    'mobile_no' => null,
                    'status' => 'PENDING'
                ]);
        }

        return redirect()
            ->route('discount-rules.index')
            ->with('success', 'Rule Added Successfully');
    }

    public function edit($id)
    {
        $rule = DiscountApprovalRule::findOrFail($id);

        return view('discount_rules.edit', compact('rule'));
    }

    public function update(Request $request, $id)
    {
        $rule = DiscountApprovalRule::findOrFail($id);

        $rule->update($request->all());

        return redirect()->route('discount-rules.index')
        ->with('success','Updated Successfully');
    }

    public function destroy($id)
    {
        DiscountApprovalRule::findOrFail($id)->delete();

        return back()->with('success','Deleted Successfully');
    }
}