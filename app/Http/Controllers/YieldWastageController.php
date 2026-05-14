<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YieldWastageController extends Controller
{
   public function index()
{

    $reports = DB::table('ingredient_masters')

        ->select(

            'ingredient_code',
            'ingredient_name',
            'purchase_uom',
            'purchase_cost',
            'yield_percent',
            'wastage_allowance_percent',
            'supplier',
            'remarks',
            'last_updated',

            // AP WEIGHT IN GM
            DB::raw('
                ROUND(
                    CASE

                        WHEN LOWER(purchase_uom) = "kg"
                        THEN purchase_qty * 1000

                        WHEN LOWER(purchase_uom) = "ltr"
                        THEN purchase_qty * 1000

                        ELSE purchase_qty

                    END
                ,2) as ap_weight
            '),

            // EP WEIGHT
            DB::raw('
                ROUND(
                    (
                        CASE

                            WHEN LOWER(purchase_uom) = "kg"
                            THEN purchase_qty * 1000

                            WHEN LOWER(purchase_uom) = "ltr"
                            THEN purchase_qty * 1000

                            ELSE purchase_qty

                        END
                    )
                    *
                    (yield_percent / 100)
                ,2) as ep_weight
            '),

            // TOTAL LOSS
            DB::raw('
                ROUND(
                    (
                        CASE

                            WHEN LOWER(purchase_uom) = "kg"
                            THEN purchase_qty * 1000

                            WHEN LOWER(purchase_uom) = "ltr"
                            THEN purchase_qty * 1000

                            ELSE purchase_qty

                        END
                    )
                    -
                    (
                        (
                            CASE

                                WHEN LOWER(purchase_uom) = "kg"
                                THEN purchase_qty * 1000

                                WHEN LOWER(purchase_uom) = "ltr"
                                THEN purchase_qty * 1000

                                ELSE purchase_qty

                            END
                        )
                        *
                        (yield_percent / 100)
                    )
                ,2) as total_loss
            '),

            // TRIM LOSS
            DB::raw('
                ROUND(
                    (
                        CASE

                            WHEN LOWER(purchase_uom) = "kg"
                            THEN purchase_qty * 1000

                            WHEN LOWER(purchase_uom) = "ltr"
                            THEN purchase_qty * 1000

                            ELSE purchase_qty

                        END
                    )
                    *
                    (wastage_allowance_percent / 100)
                ,2) as trim_loss
            '),

            // COOKING LOSS
            DB::raw('
                ROUND(
                    (
                        (
                            CASE

                                WHEN LOWER(purchase_uom) = "kg"
                                THEN purchase_qty * 1000

                                WHEN LOWER(purchase_uom) = "ltr"
                                THEN purchase_qty * 1000

                                ELSE purchase_qty

                            END
                        )
                        -
                        (
                            (
                                CASE

                                    WHEN LOWER(purchase_uom) = "kg"
                                    THEN purchase_qty * 1000

                                    WHEN LOWER(purchase_uom) = "ltr"
                                    THEN purchase_qty * 1000

                                    ELSE purchase_qty

                                END
                            )
                            *
                            (yield_percent / 100)
                        )
                    )
                    -
                    (
                        (
                            CASE

                                WHEN LOWER(purchase_uom) = "kg"
                                THEN purchase_qty * 1000

                                WHEN LOWER(purchase_uom) = "ltr"
                                THEN purchase_qty * 1000

                                ELSE purchase_qty

                            END
                        )
                        *
                        (wastage_allowance_percent / 100)
                    )
                ,2) as cooking_loss
            '),

            // AP COST
            DB::raw('
                ROUND(
                    purchase_cost
                ,2) as ap_cost
            '),

            // AP COST PER GM
            DB::raw('
                ROUND(
                    purchase_cost
                    /
                    NULLIF(
                        (
                            CASE

                                WHEN LOWER(purchase_uom) = "kg"
                                THEN purchase_qty * 1000

                                WHEN LOWER(purchase_uom) = "ltr"
                                THEN purchase_qty * 1000

                                ELSE purchase_qty

                            END
                        )
                    ,0)
                ,9) as ap_cost_per_gm
            '),

            // EP COST PER GM
            DB::raw('
                ROUND(
                    purchase_cost
                    /
                    NULLIF(
                        (
                            (
                                CASE

                                    WHEN LOWER(purchase_uom) = "kg"
                                    THEN purchase_qty * 1000

                                    WHEN LOWER(purchase_uom) = "ltr"
                                    THEN purchase_qty * 1000

                                    ELSE purchase_qty

                                END
                            )
                            *
                            (yield_percent / 100)
                        )
                    ,0)
                ,9) as ep_cost_per_gm
            '),

            // COST INCREASE FACTOR
            DB::raw('
                ROUND(
                    (
                        purchase_cost
                        /
                        NULLIF(
                            (
                                (
                                    CASE

                                        WHEN LOWER(purchase_uom) = "kg"
                                        THEN purchase_qty * 1000

                                        WHEN LOWER(purchase_uom) = "ltr"
                                        THEN purchase_qty * 1000

                                        ELSE purchase_qty

                                    END
                                )
                                *
                                (yield_percent / 100)
                            )
                        ,0)
                    )
                    /
                    (
                        purchase_cost
                        /
                        NULLIF(
                            (
                                CASE

                                    WHEN LOWER(purchase_uom) = "kg"
                                    THEN purchase_qty * 1000

                                    WHEN LOWER(purchase_uom) = "ltr"
                                    THEN purchase_qty * 1000

                                    ELSE purchase_qty

                                END
                            )
                        ,0)
                    )
                ,3) as cost_increase_factor
            ')

        )

        ->orderBy('ingredient_name')

        ->get();

    return view(
        'yield_wastage.index',
        compact('reports')
    );
}


public function ingredientWastageReport()
{
    $reports = DB::table('recipe_items as ri')

        // MENU ITEMS
        ->leftJoin(
            'menu_items as mi',
            'ri.menu_item_id',
            '=',
            'mi.id'
        )

        // INGREDIENT MASTER
        ->leftJoin('ingredient_masters as im', function ($join) {

            $join->on(
                DB::raw('ri.component_name COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('im.ingredient_name COLLATE utf8mb4_unicode_ci')
            );

        })

        ->select(

            'mi.item_name',

            'ri.component_name as ingredient_name',

            'ri.quantity as used_qty',

            'im.purchase_qty',

            // REMAINING
            DB::raw('
                ROUND(
                    IFNULL(im.purchase_qty,0)
                    -
                    IFNULL(ri.quantity,0),
                2)
                as remaining_qty
            ')
        )

        ->where('ri.component_type', 'INGREDIENT')

        ->orderBy('mi.item_name')

        ->get();

    return view(
        'yield_wastage.Ingredient',
        compact('reports')
    );
}


}