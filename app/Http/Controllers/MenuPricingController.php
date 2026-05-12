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

    public function calculate($id)
    {
        $menuItem = MenuItem::find($id);

        $plateCost = $menuItem->plate_cost;

        $targetFoodCost = $menuItem->target_food_cost_percent;

        $suggestedPrice = 0;

        if($targetFoodCost > 0){

            $suggestedPrice = $plateCost /
                ($targetFoodCost / 100);
        }

        $gstRate = $menuItem->gst_rate;

        $gstAmount = ($suggestedPrice * $gstRate) / 100;

        $priceIncludingGST = $suggestedPrice + $gstAmount;

        $roundedPrice = round($priceIncludingGST);

        $menuItem->update([
            'suggested_price' => $suggestedPrice,
            'price_including_gst' => $priceIncludingGST,
            'rounded_price' => $roundedPrice,
        ]);

        return redirect()->back()
            ->with('success','Menu Price Updated');
    }
}