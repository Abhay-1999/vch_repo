<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;

class ContributionMarginController extends Controller
{
    public function calculate($id)
    {
        $menuItem = MenuItem::find($id);

        $sellingPrice = $menuItem->actual_price;

        $plateCost = $menuItem->plate_cost;

        $contributionMargin = $sellingPrice - $plateCost;

        $foodCostPercent = 0;

        if($sellingPrice > 0){

            $foodCostPercent =
                ($plateCost / $sellingPrice) * 100;
        }

        $menuItem->update([
            'contribution_margin' => $contributionMargin,
            'target_food_cost_percent' => $foodCostPercent,
        ]);

        return redirect()->back()
            ->with('success','Contribution Margin Updated');
    }
}