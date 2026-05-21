<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use DB;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        $discounts = DB::table('discount_master')->get();

        return view('coupons.create',compact('discounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|unique:coupons',
            'usage_limit' => 'required|integer',
        ]);

        Coupon::create($request->all());

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon created successfully');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $discounts = DB::table('discount_master')->get();

        return view('coupons.edit', compact('coupon','discounts'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update($request->all());

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon updated successfully');
    }

    public function destroy($id)
    {
        Coupon::destroy($id);

        return back()->with('success', 'Coupon deleted');
    }
}