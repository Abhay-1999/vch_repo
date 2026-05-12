<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\SubRecipe;
use App\Models\SubRecipeItem;
use Illuminate\Http\Request;

class SubRecipeController extends Controller
{
    public function index()
    {
        $subRecipes = SubRecipe::latest()->get();

        return view('sub_recipes.index', compact('subRecipes'));
    }

    public function create()
    {
        $ingredients = Ingredient::all();

        $lastIngredient = SubRecipe::latest('id')->first();

        if ($lastIngredient) {

            // Get last number
            $lastNumber = (int) str_replace('SR-', '', $lastIngredient->sub_recipe_code);

            // Increment
            $newNumber = $lastNumber + 1;

        } else {

            $newNumber = 1;
        }

        // Generate Code
        $sub_code = 'SR-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);


        return view('sub_recipes.create', compact('ingredients','sub_code'));
    }

    public function store(Request $request)
    {
        $subRecipe = SubRecipe::create([
            'sub_recipe_code' => $request->sub_recipe_code,
            'sub_recipe_name' => $request->sub_recipe_name,
            'batch_output'    => $request->batch_output,
        ]);

        $totalCost = 0;

        foreach ($request->ingredient_id as $key => $ingredientId) {

            $ingredient = Ingredient::find($ingredientId);

            $qty = $request->qty[$key];

            $lineCost = $qty * $ingredient->costing_rate;

            SubRecipeItem::create([
                'sub_recipe_id' => $subRecipe->id,
                'ingredient_id' => $ingredientId,
                'quantity_used' => $qty,
                'costing_rate'  => $ingredient->costing_rate,
                'line_cost'     => $lineCost,
            ]);

            $totalCost += $lineCost;
        }

        $costPerGram = 0;

        if($request->batch_output > 0){
            $costPerGram = $totalCost / $request->batch_output;
        }

        $subRecipe->update([
            'total_cost'   => $totalCost,
            'cost_per_gram'=> $costPerGram,
        ]);

        return redirect()->route('sub-recipes.index')
            ->with('success','Sub Recipe Created');
    }
}