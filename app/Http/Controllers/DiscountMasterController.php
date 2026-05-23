<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DiscountMasterController extends Controller
{

    /**
     * Display all discounts
     */
    public function index()
    {
        $discounts = DiscountMaster::latest('id')->get();

        return view('discount_master.index', compact('discounts'));
    }


    public function create()
    {
        $items = DB::table('menu_items')
                    ->select('id', 'item_name', 'category')
                    ->orderBy('item_name', 'ASC')
                    ->get();


        // Last Discount ID
        $lastDiscount = DiscountMaster::orderBy('id', 'DESC')->first();

        if($lastDiscount)
        {
            $lastNumber = intval(
                str_replace(
                    'DSC-',
                    '',
                    $lastDiscount->discount_id
                )
            );

            $newNumber = $lastNumber + 1;
        }
        else
        {
            $newNumber = 1;
        }


        // Generate New Discount ID
        $discount_id = 'DSC-' .
                        str_pad(
                            $newNumber,
                            4,
                            '0',
                            STR_PAD_LEFT
                        );


        return view(
            'discount_master.create',
            compact(
                'items',
                'discount_id'
            )
        );
    }

    /**
     * Store discount data
     */
    public function store(Request $request)
    {
        $request->validate([

            'name'            => 'required|max:80',
            'type'            => 'required|max:50',
            'value'           => 'required|numeric',
            'unit'            => 'required|max:10',
            'max_cap'         => 'nullable|numeric',
            'min_bill'        => 'nullable|numeric',
            'applies_to'      => 'required|max:100',
            'approval_level'  => 'nullable|max:20',
            'valid_from'      => 'required|date',
            'valid_to'        => 'required|date',
            'status'          => 'required|max:20',

        ]);


        // =========================
        // Generate Discount ID
        // =========================

        $lastDiscount = DiscountMaster::orderBy('id', 'DESC')->first();

        if($lastDiscount)
        {
            $lastNumber = intval(
                str_replace(
                    'DSC-',
                    '',
                    $lastDiscount->discount_id
                )
            );

            $newNumber = $lastNumber + 1;
        }
        else
        {
            $newNumber = 1;
        }


        $discountId = 'DSC-' .
                        str_pad(
                            $newNumber,
                            4,
                            '0',
                            STR_PAD_LEFT
                        );


        // =========================
        // Save Discount
        // =========================

        DiscountMaster::create([

            'discount_id'     => $discountId,

            'name'            => $request->name,

            'type'            => $request->type,

            'value'           => $request->value,

            'unit'            => $request->unit,

            'max_cap'         => $request->max_cap ?? 0,

            'min_bill'        => $request->min_bill ?? 0,

            'applies_to'      => $request->applies_to,

            'stackable'       => $request->stackable ?? 0,

            'auto_apply'      => $request->auto_apply ?? 0,

            'approval_req'    => $request->approval_req ?? 0,

            'approval_level'  => $request->approval_level ?? 'Auto',

            'valid_from'      => $request->valid_from,

            'valid_to'        => $request->valid_to,

            'active_days'     => $request->active_days ?? 'All',

            'active_hours'    => $request->active_hours ?? 'All',

            'channel'         => $request->channel ?? 'All',

            'outlet'          => $request->outlet ?? 'All Outlets',

            'status'          => $request->status,

            'created_by'      => auth()->guard('admin')->user()->name ?? 'Admin',

            'created_on'      => Carbon::now(),

            'remarks'         => $request->remarks,

        ]);


        return redirect()
                ->route('discount-master.index')
                ->with(
                    'success',
                    'Discount Created Successfully'
                );
    }


  
    public function edit($id)
    {
        $discount = DiscountMaster::findOrFail($id);
        $items = DB::table('menu_items')
                    ->select('id', 'item_name', 'category')
                    ->orderBy('item_name', 'ASC')
                    ->get();
        return view(
            'discount_master.edit',
            compact(
                'discount',
                'items'
            )
        );
    }


    public function update(Request $request, $id)
    {
        $request->validate([

            'name'            => 'required|max:80',
            'type'            => 'required|max:50',
            'value'           => 'required|numeric',
            'unit'            => 'required|max:10',
            'max_cap'         => 'nullable|numeric',
            'min_bill'        => 'nullable|numeric',
            'applies_to'      => 'required|max:100',
            'approval_level'  => 'nullable|max:20',
            'valid_from'      => 'required|date',
            'valid_to'        => 'required|date',
            'status'          => 'required|max:20',

        ]);
        $discount = DiscountMaster::findOrFail($id);
        $discount->update([
            'name'            => $request->name,
            'type'            => $request->type,
            'value'           => $request->value,
            'unit'            => $request->unit,
            'max_cap'         => $request->max_cap ?? 0,
            'min_bill'        => $request->min_bill ?? 0,
            'applies_to'      => $request->applies_to,
            'stackable'       => $request->stackable ?? 0,
            'auto_apply'      => $request->auto_apply ?? 0,
            'approval_req'    => $request->approval_req ?? 0,
            'approval_level'  => $request->approval_level ?? 'Auto',
            'valid_from'      => $request->valid_from,
            'valid_to'        => $request->valid_to,
            'active_days'     => $request->active_days ?? 'All',
            'active_hours'    => $request->active_hours ?? 'All',
            'channel'         => $request->channel ?? 'All',
            'outlet'          => $request->outlet ?? 'All Outlets',
            'status'          => $request->status,
            'remarks'         => $request->remarks,
        ]);
        return redirect()
                ->route('discount-master.index')
                ->with(
                    'success',
                    'Discount Updated Successfully'
                );
    }



    public function destroy($id)
    {
        $discount = DiscountMaster::findOrFail($id);
        $discount->delete();
        return redirect()
                ->route('discount-master.index')
                ->with(
                    'success',
                    'Discount Deleted Successfully'
                );
    }
}