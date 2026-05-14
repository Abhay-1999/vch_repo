<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
class MenuEngineeringController extends Controller
{
        public function index()
    {
        $items = MenuItem::all();

        // Total Units Sold
        $totalUnitsSold = DB::table('order_dt')
            ->sum('item_qty');

        // Total Margin
        $totalMarginAll = 0;

        $tempRows = [];

        foreach ($items as $item) {

            // Units Sold
            $unitsSold = DB::table('order_dt')
                ->where('item_code', $item->item_code)
                ->sum('item_qty');

            // Selling Price ex GST
            $sellingPrice = $item->actual_price ?? 0;

            // Plate Cost
            $plateCost = $item->plate_cost ?? 0;

            // Contribution Margin
            $contributionMargin = $sellingPrice - $plateCost;

            // Total Sales
            $totalSales = $unitsSold * $sellingPrice;

            // Total Cost
            $totalCost = $unitsSold * $plateCost;

            // Total Margin
            $totalMargin = $totalSales - $totalCost;

            $totalMarginAll += $totalMargin;

            $tempRows[] = [
                'item_code' => $item->item_code,
                'item_name' => $item->item_name,
                'units_sold' => $unitsSold,
                'plate_cost' => $plateCost,
                'selling_price' => $sellingPrice,
                'contribution_margin' => $contributionMargin,
                'total_sales' => $totalSales,
                'total_cost' => $totalCost,
                'total_margin' => $totalMargin,
            ];
        }

        // Average values
        $avgSalesMix = 100 / max(count($items), 1);

        $avgMargin = 0;

        if(count($items) > 0){
            $avgMargin = collect($tempRows)->avg('contribution_margin');
        }

        $report = [];

        foreach ($tempRows as $row) {

            // Sales Mix %
            $salesMix = 0;

            if($totalUnitsSold > 0){
                $salesMix = ($row['units_sold'] / $totalUnitsSold) * 100;
            }

            // Margin Mix %
            $marginMix = 0;

            if($totalMarginAll > 0){
                $marginMix = ($row['total_margin'] / $totalMarginAll) * 100;
            }

            // Popularity
            $popularity = $salesMix >= $avgSalesMix
                ? 'HIGH'
                : 'LOW';

            // Profitability
            $profitability = $row['contribution_margin'] >= $avgMargin
                ? 'HIGH'
                : 'LOW';

            // Classification
            $classification = '';
            $action = '';

            if($popularity == 'HIGH' && $profitability == 'HIGH') {

                $classification = 'STAR';

                $action =
                    'Maintain quality, feature on menu prominently';

            }
            elseif($popularity == 'LOW' && $profitability == 'HIGH') {

                $classification = 'PUZZLE';

                $action =
                    'Reposition / promote / rename / re-photograph';

            }
            elseif($popularity == 'HIGH' && $profitability == 'LOW') {

                $classification = 'PLOWHORSE';

                $action =
                    'Re-engineer recipe to cut cost, or raise price gently';

            }
            else {

                $classification = 'DOG';

                $action =
                    'Consider removing from menu or re-conceive';
            }

            $report[] = [

                'item_code' => $row['item_code'],
                'item_name' => $row['item_name'],
                'units_sold' => $row['units_sold'],
                'plate_cost' => $row['plate_cost'],
                'selling_price' => $row['selling_price'],
                'contribution_margin' => $row['contribution_margin'],
                'total_sales' => $row['total_sales'],
                'total_cost' => $row['total_cost'],
                'total_margin' => $row['total_margin'],
                'sales_mix' => round($salesMix,1),
                'margin_mix' => round($marginMix,1),
                'popularity' => $popularity,
                'profitability' => $profitability,
                'classification' => $classification,
                'action' => $action,
            ];
        }

        return view(
            'menu_engineering.index',
            compact(
                'report',
                'totalUnitsSold',
                'totalMarginAll'
            )
        );
    }
}