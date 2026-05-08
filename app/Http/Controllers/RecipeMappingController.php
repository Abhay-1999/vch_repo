<?php

namespace App\Http\Controllers;

use App\Models\RecipeMapping;
use Illuminate\Http\Request;

class RecipeMappingController extends Controller
{

        public function recipe_mapping_index()
        {
            $recipes = RecipeMapping::orderBy('id', 'desc')->get();

            return view('recipe_mapping.index', compact('recipes'));
        }



       public function recipe_mapping_form()
        {
            $lastRecipe = \DB::table('recipe_mappings')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastRecipe && !empty($lastRecipe->recipe_id)) {
                $lastNumber = (int) str_replace('RCP-', '', $lastRecipe->recipe_id);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $recipe_id = 'RCP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        
            $materials = \DB::table('raw_material_master')
                ->select('material_code', 'material_name')
                ->orderBy('material_name', 'asc')
                ->get();

        
            $items = \DB::table('item_master')
                ->select('item_code', 'item_desc')
                ->orderBy('item_desc', 'asc')
                ->get();

            return view('recipe_mapping.create', compact('recipe_id', 'materials', 'items'));
        }


        public function recipe_mapping_store(Request $request)
        {
            $request->validate([
                'recipe_id' => 'required',
                'item_code' => 'required',
                'item_desc' => 'required',
                'material_code' => 'required',
                'material_name' => 'required',
            ]);

            \DB::table('recipe_mappings')->insert([
                'recipe_id'          => $request->recipe_id,
                'item_code'          => $request->item_code,
                'item_name'          => $request->item_desc, // 👈 fix mapping
                'selling_price'      => $request->selling_price,
                'standard_yield'     => $request->standard_yield,

                'material_code'      => $request->material_code,
                'material_name'      => $request->material_name,

                'qty_per_serving'    => $request->qty_per_serving,
                'recipe_uom'         => $request->recipe_uom,
                'qty_in_base_uom'    => $request->qty_in_base_uom,
                'cost_per_base_uom'  => $request->cost_per_base_uom,
                'ingredient_cost'    => $request->ingredient_cost,
                'wastage_allowance'  => $request->wastage_allowance,
                'effective_cost'     => $request->effective_cost,

                'active'             => $request->active ?? 1,
                'effective_from'     => $request->effective_from,
                'effective_to'       => $request->effective_to,

                'created_by'         => $request->created_by,
                'approved_by'        => $request->approved_by,
                'remarks'            => $request->remarks,

            ]);

            return redirect()
                ->route('recipe_mapping_index')
                ->with('success', 'Recipe Mapping Saved Successfully');
        }

        public function show(RecipeMapping $recipeMapping)
        {
            //
        }


        public function edit(RecipeMapping $recipeMapping)
        {
            //
        }


        public function update(Request $request, RecipeMapping $recipeMapping)
        {
            //
        }

 
        public function destroy(RecipeMapping $recipeMapping)
        {
            //
        }
}
