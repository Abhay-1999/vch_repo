<?php

namespace App\Services;

use App\Models\RecipeItem;
use App\Models\IngredientMaster;
use App\Models\StockLedger;
use App\Models\MenuItem;
use App\Models\SubRecipeItem;

class InventoryService
{
    public static function deductStock($itemCode, $saleQty, $referenceNo)
    {

        $menu_item_id = MenuItem::where(
            'item_code',
            $itemCode
        )->value('id');

        $recipes = RecipeItem::where(
            'menu_item_id',
            $menu_item_id
        )->get();
    
        foreach ($recipes as $recipe) {
    
            // ====================================
            // DIRECT INGREDIENT
            // ====================================
    
            if ($recipe->component_type == 'INGREDIENT') {
    
                self::deductIngredient(
                    $recipe->component_code,
                    $recipe->quantity,
                    $saleQty,
                    $referenceNo
                );
            }
    
    
            // ====================================
            // SUB RECIPE
            // ====================================
    
            if ($recipe->component_type == 'SUB_RECIPE') {
    
                $subRecipes = SubRecipeItem::where(
                    'sub_recipe_id',
                    $recipe->component_code
                )->get();
    
                foreach ($subRecipes as $sub) {
    
                    $finalQty = (
                        $sub->quantity_used
                        * $recipe->quantity
                    );
    
                    self::deductIngredient(
                        $sub->ingredient_id,
                        $finalQty,
                        $saleQty,
                        $referenceNo
                    );
                }
            }
        }
    }


    private static function deductIngredient(
        $ingredientCode,
        $recipeQty,
        $saleQty,
        $referenceNo
    )
    {
        //  echo $ingredientCode.'--'.$recipeQty.'--'.$recipeQty.'--'.$referenceNo;die;
        $ingredient = IngredientMaster::where(
            'id',
            $ingredientCode
        )->first();
    
        if (!$ingredient) {
            return;
        }
    
        // ======================
        // TOTAL CONSUMPTION
        // ======================
    
        $consumeQty = $recipeQty * $saleQty;
    
        // ======================
        // STOCK CHECK
        // ======================
    
        if ($ingredient->current_stock < $consumeQty) {
    
            throw new \Exception(
                $ingredient->ingredient_name .
                ' stock not available'
            );
        }
    
        $before = $ingredient->current_stock;
    
        $after = $before - $consumeQty;
    
        // ======================
        // UPDATE STOCK
        // ======================
    
        $ingredient->update([
            'current_stock' => $after
        ]);
    
        // ======================
        // STOCK LEDGER
        // ======================
    
        StockLedger::create([
            'material_id'  => $ingredient->id,
            'type'         => 'SALE',
            'reference_no' => $referenceNo,
            'qty'          => $consumeQty,
            'stock_before' => $before,
            'stock_after'  => $after,
            'remarks'      => 'Auto stock deduction'
        ]);
    }
}