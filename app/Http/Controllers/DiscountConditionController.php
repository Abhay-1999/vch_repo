<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountCondition;

class DiscountConditionController extends Controller
{
    public function index()
    {
      $conditions = DiscountCondition::with('discount')
    ->orderBy('condition_id', 'desc')
    ->get();

        return view('discount_conditions.index', compact('conditions'));
    }
    public function create()
    {
        $last = \App\Models\DiscountCondition::whereNotNull('condition_id')
            ->orderBy('condition_id', 'desc')
            ->first();

        if (!$last) {
            $newId = 'CND-0001';
        } else {
            $number = (int) str_replace('CND-', '', $last->condition_id);
            $number++;
            $newId = 'CND-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }

        $discountMasters = \App\Models\DiscountMaster::select('discount_id', 'name', 'type')->get();

        return view('discount_conditions.create', compact('newId', 'discountMasters'));
    }


   public function store(Request $request)
{
    $request->validate([

        'discount_id'     => 'required',

        'condition_type'  => 'required|array',

        'operator'        => 'required|array',

        'value'           => 'required|array',

    ]);


    foreach ($request->condition_type as $i => $type)
    {

        DiscountCondition::create([

            'discount_id'    => $request->discount_id,

            'condition_type' => $type,

            'operator'       => $request->operator[$i] ?? null,

            'value'          => $request->value[$i] ?? null,

            'note'           => $request->note[$i] ?? null,

        ]);
    }


    return redirect()
            ->route('discount-conditions.index')
            ->with(
                'success',
                'Discount conditions saved successfully'
            );
}
}