<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\RecipeItem;
use App\Models\SubRecipe;
use Illuminate\Http\Request;

class RecipeCostController extends Controller
{
    public function index()
    {
        return view('recipe_cost.index'); 
        // ya jo bhi tumhara blade file hai
    }
    
    public function create()
    {
        $ingredients = Ingredient::all();
        $subRecipes  = SubRecipe::all();

        $lastIngredient = MenuItem::latest('id')->first();

        if ($lastIngredient) {

            // Get last number
            $lastNumber = (int) str_replace('ITM-', '', $lastIngredient->item_code);

            // Increment
            $newNumber = $lastNumber + 1;

        } else {

            $newNumber = 1;
        }

        // Generate Code
        $ITEM_CODE = 'ITM-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('recipe_cost.create', compact(
            'ingredients',
            'subRecipes','ITEM_CODE'
        ));
    }

    public function store(Request $request)
    {
        // echo"<pre>";print_r($request->all());die;

        $menuItem = MenuItem::create([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'category'  => $request->category,
            'servings_per_recipe' => $request->servings,
        ]);

        $totalRecipeCost = 0;

        foreach ($request->component_type as $key => $type) {

            //echo $type;die;

            if($type == 'INGREDIENT'){  

                $ingredient = Ingredient::find(
                    $request->component_id[$key]
                );

                // echo"<pre>";print_r($ingredient);die;

                $costRate = $ingredient->costing_rate;

                $componentName = $ingredient->ingredient_name;
            }
            else{

                $subRecipe = SubRecipe::find(
                    $request->component_id[$key]
                );

                // echo"<pre>";print_r($subRecipe->toArray());die;

                $costRate = $subRecipe->cost_per_gram;

                $componentName = $subRecipe->sub_recipe_name;
            }

            $qty = $request->qty[$key];

            $componentCost = $qty * $costRate;

            RecipeItem::create([
                'menu_item_id' => $menuItem->id,
                'component_type' => $type,
                'component_code' => $request->component_id[$key],
                'component_name' => $componentName,
                'quantity' => $qty,
                'cost_rate' => $costRate,
                'component_cost' => $componentCost,
            ]);

            $totalRecipeCost += $componentCost;
        }

        $plateCost = 0;

        if($request->servings > 0){
            $plateCost = $totalRecipeCost / $request->servings;
        }

        $menuItem->update([
            'plate_cost' => $plateCost
        ]);

        return redirect()->back()
            ->with('success','Recipe Cost Calculated');
    }
}