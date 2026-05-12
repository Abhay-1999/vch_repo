<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Sale;

class MenuEngineeringController extends Controller
{
    public function index()
    {
        $items = MenuItem::all();

        $totalQtySold = Sale::sum('qty_sold');

        $avgContribution = MenuItem::avg('contribution_margin');

        $report = [];

        foreach ($items as $item) {

            $qtySold = Sale::where(
                'menu_item_id',
                $item->id
            )->sum('qty_sold');

            $salesMix = 0;

            if($totalQtySold > 0){
                $salesMix = ($qtySold / $totalQtySold) * 100;
            }

            $popularityStatus = 'LOW';

            if($salesMix >= 70){
                $popularityStatus = 'HIGH';
            }

            $profitStatus = 'LOW';

            if($item->contribution_margin >= $avgContribution){
                $profitStatus = 'HIGH';
            }

            $menuClass = '';

            if($popularityStatus == 'HIGH' &&
                $profitStatus == 'HIGH'){

                $menuClass = 'STAR';
            }
            elseif($popularityStatus == 'LOW' &&
                $profitStatus == 'HIGH'){

                $menuClass = 'PUZZLE';
            }
            elseif($popularityStatus == 'HIGH' &&
                $profitStatus == 'LOW'){

                $menuClass = 'PLOWHORSE';
            }
            else{
                $menuClass = 'DOG';
            }

            $report[] = [
                'item_name' => $item->item_name,
                'qty_sold' => $qtySold,
                'sales_mix' => round($salesMix,2),
                'contribution_margin' =>
                    round($item->contribution_margin,2),
                'menu_class' => $menuClass,
            ];
        }

        return view(
            'menu_engineering.index',
            compact('report')
        );
    }
}