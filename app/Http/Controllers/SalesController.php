<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
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