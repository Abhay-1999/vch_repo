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

        // Form Values
        $targetFoodCost = $request->target_fc;
        $gstRate = $request->gst_rate;

        $plateCost = $menuItem->plate_cost;

        // Suggested Price
        $suggestedPrice = 0;

        if ($targetFoodCost > 0) {

            $suggestedPrice =
                $plateCost / ($targetFoodCost / 100);
        }

        // GST Calculation
        $gstAmount =
            ($suggestedPrice * $gstRate) / 100;

        $priceIncludingGST =
            $suggestedPrice + $gstAmount;

        // Rounded Price
        $roundedPrice = round($priceIncludingGST);

        // Update
        $menuItem->update([

            'target_food_cost_percent' => $targetFoodCost,

            'gst_rate' => $gstRate,

            'suggested_price' => $suggestedPrice,

            'price_including_gst' => $priceIncludingGST,

            'rounded_price' => $roundedPrice,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Menu Price Updated Successfully');
    }
}