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

                'purchase_qty as ap_weight',

                DB::raw('ROUND((purchase_qty * wastage_allowance_percent / 100),3) as trim_loss'),

                DB::raw('ROUND((purchase_qty * (100 - yield_percent) / 100),3) as cooking_loss'),

                DB::raw('ROUND((purchase_qty * yield_percent / 100),3) as ep_weight'),

                'yield_percent',

                'purchase_cost as ap_cost',

                DB::raw('
                    ROUND(
                        purchase_cost /
                        NULLIF((purchase_qty * yield_percent / 100),0)
                    ,9)
                    as ep_cost_per_gm
                '),

                DB::raw('
                    ROUND(
                        purchase_cost /
                        NULLIF(purchase_qty,0)
                    ,9)
                    as ap_cost_per_gm
                '),

                DB::raw('
                    ROUND(
                        (
                            purchase_cost /
                            NULLIF((purchase_qty * yield_percent / 100),0)
                        )
                        /
                        (
                            purchase_cost /
                            NULLIF(purchase_qty,0)
                        )
                    ,3)
                    as cost_increase_factor
                '),

                'supplier',

                'last_updated',

                'remarks'

            )

            ->orderBy('ingredient_name')

            ->get();

        return view(
            'yield_wastage.index',
            compact('reports')
        );
    }
}