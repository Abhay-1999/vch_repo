<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuPricingController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();

        return view('menu_pricing.index', compact('menuItems'));
    }

    public function calculate(Request $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);
    
        // Inputs
        $targetFoodCost = $request->target_fc;
        $gstRate = $request->gst_rate;
        $actualPrice = $request->actual_price;
    
        $plateCost = $menuItem->plate_cost;
    
        // Suggested Price
        $suggestedPrice = 0;
    
        if ($targetFoodCost > 0) {
    
            $suggestedPrice =
                $plateCost / ($targetFoodCost / 100);
        }
    
        // GST
        $gstAmount =
            ($suggestedPrice * $gstRate) / 100;
    
        $priceIncludingGST =
            $suggestedPrice + $gstAmount;
    
        // Rounded Price
        $roundedPrice = round($priceIncludingGST);
    
        // Actual Food Cost %
        $actualFoodCostPercent = 0;
    
        if ($actualPrice > 0) {
    
            $actualFoodCostPercent =
                ($plateCost / $actualPrice) * 100;
        }
    
        // Variance
        $variance =
            $actualFoodCostPercent - $targetFoodCost;
    
        // Status
        if ($variance > 5) {
    
            $status = 'UNDERPRICED';
    
        } elseif ($variance < -5) {
    
            $status = 'OVERPRICED';
    
        } else {
    
            $status = 'OPTIMAL';
        }
    
        // Contribution Margin
        $contributionMargin =
            $actualPrice - $plateCost;
    
        // Update
        $menuItem->update([
    
            'target_food_cost_percent' => $targetFoodCost,
    
            'gst_rate' => $gstRate,
    
            'suggested_price' => round($suggestedPrice, 2),
    
            'actual_price' => $actualPrice,
    
            'price_including_gst' => round($priceIncludingGST, 2),
    
            'rounded_price' => $roundedPrice,
    
            'contribution_margin' => round($contributionMargin, 2),
    
        ]);
    
        return redirect()
            ->back()
            ->with('success', 'Menu Price Updated Successfully');
    }
}