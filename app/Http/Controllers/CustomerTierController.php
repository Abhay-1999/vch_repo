<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerTier;
use DB;

class CustomerTierController extends Controller
{
    public function index()
    {
        $tiers = CustomerTier::orderBy('id', 'desc')->get();
        return view('tiers.index', compact('tiers'));
    }

    public function create()
    {
        $discounts = DB::table('discount_master')->get();

        return view('tiers.create',compact('discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tier_name' => 'required',
        ]);

        $lastTier = CustomerTier::latest('id')->first();

        if ($lastTier) {
            $lastNumber = (int) str_replace('TR-', '', $lastTier->tier_id);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $tierId = 'TR-' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        CustomerTier::create([
            'tier_id' => $tierId,
            'tier_name' => $request->tier_name,
            'min_lifetime_spend' => $request->min_lifetime_spend,
            'max_lifetime_spend' => $request->max_lifetime_spend,
            'auto_discount_percent' => $request->auto_discount_percent,
            'discount_cap' => $request->discount_cap,
            'birthday_bonus_percent' => $request->birthday_bonus_percent,
            'reward_points_per_100' => $request->reward_points_per_100,
            'linked_discount_code' => $request->linked_discount_code,
            'color' => $request->color,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('tiers.index')->with('success', 'Tier Created');
    }

    public function edit($id)
    {
        $discounts = DB::table('discount_master')->get();

        $tier = CustomerTier::findOrFail($id);
        return view('tiers.edit', compact('tier','discounts'));
    }

    public function update(Request $request, $id)
    {
        $tier = CustomerTier::findOrFail($id);

        $tier->update($request->all());

        return redirect()->route('tiers.index')->with('success', 'Tier Updated');
    }

    public function destroy($id)
    {
        CustomerTier::destroy($id);
        return back()->with('success', 'Deleted Successfully');
    }
}