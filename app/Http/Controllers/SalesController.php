<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    public function index()
{
    $sales = DB::table('order_dt as od')

        ->join('order_hd as oh', function ($join) {

            $join->on('od.tran_no', '=', 'oh.tran_no')
                 ->on('od.tran_date', '=', 'oh.tran_date');

        })

        ->leftJoin('menu_items as mi', function ($join) {

            $join->on(
                DB::raw("RIGHT(mi.item_code, 2)"),
                '=',
                DB::raw("RIGHT(od.item_code, 2)")
            );

        })

        ->select(

            'oh.tran_date as sale_date',

            'mi.item_code',

            'mi.item_name',

            'od.item_qty as qty_sold',

            // Selling Price Ex GST
            DB::raw('
                CASE
                    WHEN od.item_qty > 0
                    THEN od.amount / od.item_qty
                    ELSE 0
                END as selling_price
            '),

            'mi.plate_cost',

            // Sales Value
            DB::raw('od.amount as sales_value'),

            // COGS
            DB::raw('(mi.plate_cost * od.item_qty) as cogs'),

            // Gross Margin
            DB::raw('
                (od.amount - (mi.plate_cost * od.item_qty))
                as gross_margin
            '),

            // Food Cost %
            DB::raw('
                CASE
                    WHEN od.amount > 0
                    THEN ((mi.plate_cost * od.item_qty) / od.amount) * 100
                    ELSE 0
                END as food_cost_percent
            '),

            // Margin %
            DB::raw('
                CASE
                    WHEN od.amount > 0
                    THEN (
                        (
                            od.amount -
                            (mi.plate_cost * od.item_qty)
                        ) / od.amount
                    ) * 100
                    ELSE 0
                END as margin_percent
            '),

            DB::raw("'' as notes")

        )

        ->orderBy('oh.tran_date', 'desc')

        ->get();

    return view('sales.index', compact('sales'));
}


    public function store(Request $request)
    {
        $menuItem = MenuItem::find($request->menu_item_id);

        $qtySold = $request->qty_sold;

        $sellingPrice = $menuItem->actual_price;

        $plateCost = $menuItem->plate_cost;

        $salesValue = $qtySold * $sellingPrice;

        $cogs = $qtySold * $plateCost;

        $grossMargin = $salesValue - $cogs;

        $foodCostPercent = 0;

        if($salesValue > 0){
            $foodCostPercent =
                ($cogs / $salesValue) * 100;
        }

        Sale::create([
            'sale_date' => now(),
            'menu_item_id' => $menuItem->id,
            'qty_sold' => $qtySold,
            'selling_price' => $sellingPrice,
            'plate_cost' => $plateCost,
            'sales_value' => $salesValue,
            'cogs' => $cogs,
            'gross_margin' => $grossMargin,
            'food_cost_percent' => $foodCostPercent,
        ]);

        return redirect()->back()
            ->with('success','Sales Added');
    }
}