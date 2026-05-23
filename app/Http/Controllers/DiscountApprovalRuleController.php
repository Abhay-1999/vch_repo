<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountApprovalRule;

class DiscountApprovalRuleController extends Controller
{
    public function index()
    {
        $rules = DiscountApprovalRule::latest()->get();

        return view('discount_rules.index', compact('rules'));
    }

    public function create()
    {
        return view('discount_rules.create');
    }

    public function store(Request $request)
{
    $last = DiscountApprovalRule::latest()->first();

    if($last)
    {
        $lastNumber = (int) substr($last->rule_id, 4);

        $newNumber = $lastNumber + 1;
    }
    else
    {
        $newNumber = 1;
    }

    $ruleId = 'APR-' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

    DiscountApprovalRule::create([

        'rule_id' => $ruleId,

        'condition' => $request->condition,

        'threshold' => $request->threshold,

        'approval_required' => $request->approval_required,

        'otp_password' => $request->otp_password,

        'audit_log' => $request->audit_log,

        'email_alert_to' => $request->email_alert_to,

        'remarks' => $request->remarks,

        'status' => 1,
    ]);

    return redirect()->route('discount-rules.index')
    ->with('success','Rule Added Successfully');
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