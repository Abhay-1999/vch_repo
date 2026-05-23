<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountCondition;
use App\Models\DiscountMaster;
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

        $discountMasters = \App\Models\DiscountMaster::select('discount_id', 'name', 'type')->get();

        return view('discount_conditions.create', compact('discountMasters'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'discount_id'    => 'required',
            'condition_type' => 'required|array',
            'operator'       => 'required|array',
            'value'          => 'required|array',
        ]);

        foreach ($request->condition_type as $i => $type) {

            \App\Models\DiscountCondition::create([
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
                    'Saved successfully'
                );
    }


       /**
     * EDIT
     */
    public function edit($id)
    {
        $firstCondition = DiscountCondition::findOrFail($id);

        // Same discount_id ki saari rows
        $conditions = DiscountCondition::where(
            'discount_id',
            $firstCondition->discount_id
        )->get();

        $discountMasters = DiscountMaster::select(
            'discount_id',
            'name',
            'type'
        )->get();

        return view(
            'discount_conditions.edit',
            compact(
                'conditions',
                'discountMasters'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'discount_id'    => 'required',
            'condition_type' => 'required|array',
            'operator'       => 'required|array',
            'value'          => 'required|array',
        ]);

        $firstCondition = DiscountCondition::findOrFail($id);

        // OLD CONDITIONS DELETE
        DiscountCondition::where(
            'discount_id',
            $firstCondition->discount_id
        )->delete();

        // NEW INSERT
        foreach ($request->condition_type as $i => $type) {

            DiscountCondition::create([

                'discount_id'    => $request->discount_id,
                'condition_type' => $type,
                'operator'       => $request->operator[$i] ?? null,
                'value'          => $request->value[$i] ?? null,
                'note'           => $request->note[$i] ?? null,
                'active'         => $request->active,

            ]);
        }

        return redirect()
            ->route('discount-conditions.index')
            ->with('success', 'Conditions updated successfully');
    }

    public function destroy($id)
    {
        $condition = DiscountCondition::findOrFail($id);

        DiscountCondition::where(
            'discount_id',
            $condition->discount_id
        )->delete();

        return redirect()
            ->route('discount-conditions.index')
            ->with('success', 'Conditions deleted successfully');
    }
}