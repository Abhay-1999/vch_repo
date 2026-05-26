<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayEndSalesReportController extends Controller
{
    /**
     * REPORT LIST + FILTER SCREEN
     */
    public function index()
    {
        $reports = DB::table('report_catalogues')
            ->where('status', 1)
            ->orderBy('report_name')
            ->get();

        return view(
            'reports.day_end_sales.index',
            compact('reports')
        );
    }

    /**
     * GENERATE REPORT
     */
    public function generate(Request $request)
    {
        $request->validate([
            'business_date' => 'required|date',
            'report_code'   => 'required',
        ]);

        $date = $request->business_date;

        /*
        |--------------------------------------------------------------------------
        | REPORT MASTER
        |--------------------------------------------------------------------------
        */

        $report = DB::table('report_catalogues')
            ->where('report_code', $request->report_code)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | RPT-0001 => DAY END SALES
        |--------------------------------------------------------------------------
        */

        if ($request->report_code == 'RPT-0001') {

            $orders = DB::table('order_hd')
                ->whereDate('tran_date', $date);

            $totalOrders = (clone $orders)->count();

            $grossSales = (clone $orders)->sum('gross_amt');

            $discount = (clone $orders)->sum('discount');

            $taxable = $grossSales - $discount;

            $cgst = (clone $orders)->sum('cgst_amt');

            $sgst = (clone $orders)->sum('sgst_amt');

            $roundOff = 0;

            $netSales = $taxable + $cgst + $sgst + $roundOff;

            $cash = (clone $orders)
                ->where('payment_mode', 'C')
                ->sum('paid_amt');

            $card = (clone $orders)
                ->where('payment_mode', 'D')
                ->sum('paid_amt');

            $upi = (clone $orders)
                ->where('payment_mode', 'U')
                ->sum('paid_amt');

            $paymentTotal = $cash + $card + $upi;

            $difference = $netSales - $paymentTotal;

            return view(
                'reports.day_end_sales.report',
                compact(
                    'report',
                    'date',
                    'totalOrders',
                    'grossSales',
                    'discount',
                    'taxable',
                    'cgst',
                    'sgst',
                    'roundOff',
                    'netSales',
                    'cash',
                    'card',
                    'upi',
                    'paymentTotal',
                    'difference'
                )
            );
        }

     /*
|--------------------------------------------------------------------------
| RPT-0002 => ITEM WISE SALES (DYNAMIC)
|--------------------------------------------------------------------------
*/

if ($request->report_code == 'RPT-0002') {

    /*
    |--------------------------------------------------------------------------
    | GET ITEM SALES DATA
    |--------------------------------------------------------------------------
    */

    $items = DB::table('order_dt as od')

        /*
        |--------------------------------------------------------------------------
        | JOIN MENU ITEMS
        |--------------------------------------------------------------------------
        */

        ->leftJoin(
            'menu_items as mi',
            'mi.item_code',
            '=',
            'od.item_code'
        )

        /*
        |--------------------------------------------------------------------------
        | JOIN ORDER HEADER
        |--------------------------------------------------------------------------
        */

        ->leftJoin(
            'order_hd as oh',
            function ($join) {

                $join->on('oh.tran_no', '=', 'od.tran_no')
                     ->on('oh.tran_date', '=', 'od.tran_date');

            }
        )

        /*
        |--------------------------------------------------------------------------
        | SELECT FIELDS
        |--------------------------------------------------------------------------
        */

        ->select(

            'od.item_code',

            'mi.item_name',

            'mi.category',

            /*
            |--------------------------------------------------------------------------
            | QTY SOLD
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(od.item_qty) as qty'),

            /*
            |--------------------------------------------------------------------------
            | GROSS REVENUE
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(od.amount) as gross'),

            /*
            |--------------------------------------------------------------------------
            | DISCOUNT
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(
                (
                    od.amount /
                    NULLIF(oh.gross_amt,0)
                ) * IFNULL(oh.discount,0)
            ) as discount'),

            /*
            |--------------------------------------------------------------------------
            | RECIPE COST
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(
                od.item_qty * IFNULL(mi.plate_cost,0)
            ) as recipe_cost')

        )

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        ->whereDate('od.tran_date', $date)

        /*
        |--------------------------------------------------------------------------
        | GROUP BY
        |--------------------------------------------------------------------------
        */

        ->groupBy(
            'od.item_code',
            'mi.item_name',
            'mi.category'
        )

        /*
        |--------------------------------------------------------------------------
        | ORDER
        |--------------------------------------------------------------------------
        */

        ->orderByDesc(DB::raw('SUM(od.item_qty)'))

        ->get();

    /*
    |--------------------------------------------------------------------------
    | TOTAL VARIABLES
    |--------------------------------------------------------------------------
    */

    $totalGross = 0;

    $totalDiscount = 0;

    $totalNet = 0;

    $totalRecipeCost = 0;

    $totalMargin = 0;

    $totalQty = 0;

    /*
    |--------------------------------------------------------------------------
    | CALCULATIONS
    |--------------------------------------------------------------------------
    */

    foreach ($items as $index => $item) {

        /*
        |--------------------------------------------------------------------------
        | NET REVENUE
        |--------------------------------------------------------------------------
        */

        $item->net_revenue
            = $item->gross - $item->discount;

        /*
        |--------------------------------------------------------------------------
        | GROSS MARGIN
        |--------------------------------------------------------------------------
        */

        $item->gross_margin
            = $item->net_revenue - $item->recipe_cost;

        /*
        |--------------------------------------------------------------------------
        | MARGIN %
        |--------------------------------------------------------------------------
        */

        $item->margin_percent
            = $item->net_revenue > 0
            ? (
                $item->gross_margin
                / $item->net_revenue
              ) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TOTALS
        |--------------------------------------------------------------------------
        */

        $totalGross += $item->gross;

        $totalDiscount += $item->discount;

        $totalNet += $item->net_revenue;

        $totalRecipeCost += $item->recipe_cost;

        $totalMargin += $item->gross_margin;

        $totalQty += $item->qty;

        /*
        |--------------------------------------------------------------------------
        | RANK
        |--------------------------------------------------------------------------
        */

        $item->rank = $index + 1;
    }

    /*
    |--------------------------------------------------------------------------
    | SALES %
    |--------------------------------------------------------------------------
    */

    foreach ($items as $item) {

        $item->sales_percent
            = $totalNet > 0
            ? (
                $item->net_revenue
                / $totalNet
              ) * 100
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL MARGIN %
    |--------------------------------------------------------------------------
    */

    $overallMarginPercent
        = $totalNet > 0
        ? ($totalMargin / $totalNet) * 100
        : 0;

    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'reports.item_wise_sales.report',
        compact(
            'report',
            'date',
            'items',
            'totalGross',
            'totalDiscount',
            'totalNet',
            'totalRecipeCost',
            'totalMargin',
            'totalQty',
            'overallMarginPercent'
        )
    );
}

      
if ($request->report_code == 'RPT-0003') {

    /*
    |--------------------------------------------------------------------------
    | GET DAILY CASH SALES FROM order_hd
    |--------------------------------------------------------------------------
    */

    $records = DB::table('order_hd')
        ->select(
            'tran_date as date',

            DB::raw("
                SUM(
                    CASE
                        WHEN payment_mode = 'C'
                        THEN paid_amt
                        ELSE 0
                    END
                ) as cash_sales
            ")
        )
        ->whereDate('tran_date', $date)
        ->groupBy('tran_date')
        ->orderBy('tran_date')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | IF NO RECORD FOUND
    |--------------------------------------------------------------------------
    */

    if ($records->count() == 0) {

        return back()->with(
            'error',
            'No records found for selected date.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL VARIABLES
    |--------------------------------------------------------------------------
    */

    $totalCashSales = 0;
    $totalReceipts = 0;
    $totalInflow = 0;
    $totalExpenses = 0;
    $totalDeposits = 0;
    $totalDifference = 0;

    /*
    |--------------------------------------------------------------------------
    | OPENING BALANCE
    |--------------------------------------------------------------------------
    */

    $openingBalance = 2000;

    /*
    |--------------------------------------------------------------------------
    | CALCULATIONS
    |--------------------------------------------------------------------------
    */

    foreach ($records as $row) {

        /*
        |--------------------------------------------------------------------------
        | DYNAMIC VALUES
        |--------------------------------------------------------------------------
        */

        $row->opening = $openingBalance;

        /*
        |--------------------------------------------------------------------------
        | OTHER RECEIPTS
        | (TEMP STATIC -> later create receipts table)
        |--------------------------------------------------------------------------
        */

        $row->other_receipts = 0;

        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        | (TEMP STATIC -> later create expense table)
        |--------------------------------------------------------------------------
        */

        $row->expenses = 1000;

        /*
        |--------------------------------------------------------------------------
        | BANK DEPOSIT
        |--------------------------------------------------------------------------
        */

        $row->bank_deposit =
            round($row->cash_sales * 0.80);

        /*
        |--------------------------------------------------------------------------
        | TOTAL INFLOW
        |--------------------------------------------------------------------------
        */

        $row->total_inflow =
            $row->opening
            + $row->cash_sales
            + $row->other_receipts;

        /*
        |--------------------------------------------------------------------------
        | CLOSING BALANCE
        |--------------------------------------------------------------------------
        */

        $row->closing_balance =
            $row->total_inflow
            - $row->expenses
            - $row->bank_deposit;

        /*
        |--------------------------------------------------------------------------
        | PHYSICAL DRAWER COUNT
        |--------------------------------------------------------------------------
        */

        $row->drawer_count =
            $row->closing_balance;

        /*
        |--------------------------------------------------------------------------
        | DIFFERENCE
        |--------------------------------------------------------------------------
        */

        $row->difference =
            $row->drawer_count
            - $row->closing_balance;

        /*
        |--------------------------------------------------------------------------
        | NEXT DAY OPENING
        |--------------------------------------------------------------------------
        */

        $openingBalance =
            $row->closing_balance;

        /*
        |--------------------------------------------------------------------------
        | TOTALS
        |--------------------------------------------------------------------------
        */

        $totalCashSales += $row->cash_sales;

        $totalReceipts += $row->other_receipts;

        $totalInflow += $row->total_inflow;

        $totalExpenses += $row->expenses;

        $totalDeposits += $row->bank_deposit;

        $totalDifference += $row->difference;
    }

    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'reports.day_book.report',
        compact(
            'report',
            'records',
            'totalCashSales',
            'totalReceipts',
            'totalInflow',
            'totalExpenses',
            'totalDeposits',
            'totalDifference'
        )
    );
}

        abort(404, 'Report not configured.');
    }
}