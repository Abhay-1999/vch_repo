<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayEndSalesReportController extends Controller
{

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

    public function generate(Request $request)
    {

        // echo"<pre>";print_r($request->all());die;
        $request->validate([
            'business_date' => 'required|date',
            'report_code'   => 'required',
        ]);
        $date = $request->business_date;
        $report = DB::table('report_catalogues')
            ->where('report_code', $request->report_code)
            ->first();
        if ($request->report_code == 'RPT-0001') {
            $orders = DB::table('order_hd')->whereDate('tran_date', $date);
            $totalOrders = (clone $orders)->count();
            $grossSales = (clone $orders)->sum('gross_amt');
            $discount = (clone $orders)->sum('discount_amount');
            $taxable = $grossSales - $discount;
            $cgst = (clone $orders)->sum('cgst_amt');
            $sgst = (clone $orders)->sum('sgst_amt');
            $roundOff = 0;
            $netSales = $taxable + $cgst + $sgst + $roundOff;
            $cash = (clone $orders)->where('payment_mode', 'C')->sum('paid_amt');
            $card = (clone $orders)->where('payment_mode', 'D')->sum('paid_amt');
            $upi = (clone $orders)->where('payment_mode', 'U')->sum('paid_amt');
            $paymentTotal = $cash + $card + $upi;
            $difference = $netSales - $paymentTotal;
            return view(
                'reports.day_end_sales.report',
                compact(
                    'report','date', 'totalOrders','grossSales','discount','taxable','cgst','sgst','roundOff','netSales','cash','card','upi','paymentTotal','difference'
                )
            );
        }
        if ($request->report_code == 'RPT-0002') {
            $items = DB::table('order_dt as od')
                ->leftJoin(
                    'menu_items as mi',
                    'mi.item_code',
                    '=',
                    'od.item_code'
                )
                ->leftJoin(
                    'order_hd as oh',
                    function ($join) {
                        $join->on('oh.tran_no', '=', 'od.tran_no')
                            ->on('oh.tran_date', '=', 'od.tran_date');
                    }
                )
                ->select(
                    'od.item_code',
                    'mi.item_name',
                    'mi.category',
                    DB::raw('SUM(od.item_qty) as qty'),
                    DB::raw('SUM(od.amount) as gross'),
                    DB::raw('SUM((od.amount / NULLIF(oh.gross_amt,0)) * IFNULL(oh.discount_amount,0)) as discount'),
                    DB::raw('SUM(od.item_qty * IFNULL(mi.plate_cost,0)) as recipe_cost')
                )
                ->whereDate('od.tran_date', $date)
                ->groupBy(
                    'od.item_code',
                    'mi.item_name',
                    'mi.category'
                )
                ->orderByDesc(DB::raw('SUM(od.item_qty)'))
                ->get();
            $totalGross = 0;
            $totalDiscount = 0;
            $totalNet = 0;
            $totalRecipeCost = 0;
            $totalMargin = 0;
            $totalQty = 0;
            foreach ($items as $index => $item) {
                $item->net_revenue = $item->gross - $item->discount;
                $item->gross_margin = $item->net_revenue - $item->recipe_cost;
                $item->margin_percent = $item->net_revenue > 0
                    ? ($item->gross_margin / $item->net_revenue) * 100
                    : 0;
                $totalGross += $item->gross;
                $totalDiscount += $item->discount;
                $totalNet += $item->net_revenue;
                $totalRecipeCost += $item->recipe_cost;
                $totalMargin += $item->gross_margin;
                $totalQty += $item->qty;
                $item->rank = $index + 1;
            }
            foreach ($items as $item) {
                $item->sales_percent = $totalNet > 0
                    ? ($item->net_revenue / $totalNet) * 100
                    : 0;
            }
            $overallMarginPercent = $totalNet > 0
                ? ($totalMargin / $totalNet) * 100
                : 0;
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
            $records = DB::table('order_hd')->select('tran_date as date',
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

            if ($records->count() == 0) {

                return back()->with(
                    'error',
                    'No records found for selected date.'
                );
            }
            $totalCashSales = 0;
            $totalReceipts = 0;
            $totalInflow = 0;
            $totalExpenses = 0;
            $totalDeposits = 0;
            $totalDifference = 0;
            $openingBalance = 2000;
            foreach ($records as $row) {
                $row->opening = $openingBalance;
                $row->other_receipts = 0;
                $row->expenses = 1000;
                $row->bank_deposit =
                    round($row->cash_sales * 0.80);

                $row->total_inflow =
                    $row->opening
                    + $row->cash_sales
                    + $row->other_receipts;

                $row->closing_balance =
                    $row->total_inflow
                    - $row->expenses
                    - $row->bank_deposit;

                $row->drawer_count =
                    $row->closing_balance;

                $row->difference =
                    $row->drawer_count
                    - $row->closing_balance;

                $openingBalance =
                    $row->closing_balance;
                $totalCashSales += $row->cash_sales;
                $totalReceipts += $row->other_receipts;
                $totalInflow += $row->total_inflow;
                $totalExpenses += $row->expenses;
                $totalDeposits += $row->bank_deposit;
                $totalDifference += $row->difference;
            }

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
        if ($request->report_code == 'RPT-013') {

            $items = DB::table('order_dt as od')

                ->leftJoin(
                    'menu_items as mi',
                    'mi.item_code',
                    '=',
                    'od.item_code'
                )
                ->leftJoin('order_hd as oh', function ($join) {

                    $join->on('oh.tran_no', '=', 'od.tran_no')
                        ->on('oh.tran_date', '=', 'od.tran_date');
                })
                ->select(

                    'mi.item_code',
                    'mi.item_name',
                    'mi.category',

                    DB::raw('SUM(IFNULL(od.item_qty,0)) as qty'),
                    DB::raw('SUM(IFNULL(od.amount,0)) as gross'),
                    DB::raw('
                        SUM(
                            (
                                CAST(IFNULL(oh.discount_amount,0) AS DECIMAL(10,2))
                                /
                                NULLIF(IFNULL(oh.gross_amt,0),0)
                            )
                            *
                            IFNULL(od.amount,0)
                        ) as discount
                    '),

                    DB::raw('
                        SUM(
                            IFNULL(mi.plate_cost,0)
                            *
                            IFNULL(od.item_qty,0)
                        ) as recipe_cost
                    ')
                )
                ->groupBy(
                    'mi.item_code',
                    'mi.item_name',
                    'mi.category'
                )->orderByDesc('qty')->get();
            $totalGross      = 0;
            $totalDiscount   = 0;
            $totalNet        = 0;
            $totalRecipeCost = 0;
            $totalMargin     = 0;
            $totalQty        = 0;
            foreach ($items as $item) {
                $item->net_revenue =
                    $item->gross - $item->discount;
                $item->gross_margin =
                    $item->net_revenue - $item->recipe_cost;
                $item->margin_percent =
                    $item->net_revenue > 0
                    ? (
                        $item->gross_margin
                        / $item->net_revenue
                    ) * 100
                    : 0;

                $totalGross += $item->gross;
                $totalDiscount += $item->discount;
                $totalNet += $item->net_revenue;
                $totalRecipeCost += $item->recipe_cost;
                $totalMargin += $item->gross_margin;
                $totalQty += $item->qty;
            }
            foreach ($items as $index => $item) {
                $item->sales_percent =
                    $totalNet > 0
                    ? (
                        $item->net_revenue
                        / $totalNet
                    ) * 100
                    : 0;
                $item->rank = $index + 1;
            }
            return view(
                'reports.item_wise_sales1.report',
                compact(
                    'report',
                    'items',
                    'totalGross',
                    'totalDiscount',
                    'totalNet',
                    'totalRecipeCost',
                    'totalMargin',
                    'totalQty'
                )
            );
        }
        if ($request->report_code == 'RPT-014') {
            $categories = DB::table('order_dt as od')
                ->leftJoin(
                    'menu_items as mi',
                    DB::raw('TRIM(mi.item_code)'),
                    '=',
                    DB::raw('TRIM(od.item_code)')
                )
                ->leftJoin('order_hd as oh', function ($join) {

                    $join->on('oh.tran_no', '=', 'od.tran_no')
                        ->on('oh.tran_date', '=', 'od.tran_date');
                })
                ->select(
                    DB::raw('IFNULL(mi.category, "Unknown") as category'),
                    DB::raw('
                        SUM(
                            IFNULL(od.item_qty,0)
                        ) as total_qty
                    '),
                    DB::raw('
                        SUM(
                            IFNULL(od.amount,0)
                        ) as gross_revenue
                    '),
                    DB::raw('
                        SUM(
                            (
                                CAST(
                                    IFNULL(oh.discount_amount,0)
                                    AS DECIMAL(10,2)
                                )
                                /
                                NULLIF(
                                    IFNULL(oh.gross_amt,0),
                                    0
                                )
                            )
                            *
                            IFNULL(od.amount,0)
                        ) as discount
                    ')
                )
                ->whereNotNull('oh.tran_no')
                ->groupBy('mi.category')
                ->orderByDesc('gross_revenue')
                ->get();
            $grandQty       = 0;
            $grandGross     = 0;
            $grandDiscount  = 0;
            $grandNet       = 0;
            foreach ($categories as $row) {
                $row->net_revenue =
                    $row->gross_revenue
                    - $row->discount;
                $grandQty += $row->total_qty;
                $grandGross += $row->gross_revenue;
                $grandDiscount += $row->discount;
                $grandNet += $row->net_revenue;
            }
            foreach ($categories as $row) {

                $row->sales_percent =
                    $grandNet > 0
                    ? (
                        $row->net_revenue
                        / $grandNet
                    ) * 100
                    : 0;
            }
            return view(
                'reports.category_wise_sales.report',
                compact(
                    'report',
                    'categories',
                    'grandQty',
                    'grandGross',
                    'grandDiscount',
                    'grandNet'
                )
            );
        }

        if ($request->report_code == 'RPT-023') {
            $asOnDate = $request->as_on_date;
            $stockCategories = $request->stock_category;
            $query = DB::table('ingredient_masters')

                ->select(
                    'ingredient_code as material_code',
                    'ingredient_name as material_name',
                    'category',
                    'base_uom',
                    DB::raw('0 as opening_qty'),
                    DB::raw('0 as inward_qty'),
                    DB::raw('0 as outward_qty'),
                    DB::raw('0 as adjustment_qty'),
                    'current_stock as current_qty',
                    'min_stock as reorder_level',
                    'costing_rate',
                    DB::raw('
                        (
                            IFNULL(current_stock,0)
                            *
                            IFNULL(costing_rate,0)
                        ) as stock_value
                    ')
                );
            if (!empty($stockCategories)) {
                $query->whereIn(
                    'category',
                    $stockCategories
                );
            }
            $stocks = $query
                ->orderBy('ingredient_name')
                ->get();
            $totalCurrentStock = 0;
            $totalStockValue = 0;
            foreach ($stocks as $row) {
                if ($row->current_qty <= 0) {
                    $row->status = 'OUT OF STOCK';
                } elseif ($row->current_qty <= $row->reorder_level) {
                    $row->status = 'REORDER';
                } else {
                    $row->status = 'OK';
                }
                $totalCurrentStock += $row->current_qty;
                $totalStockValue += $row->stock_value;
            }
            $categories = DB::table('ingredient_masters')
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');
            return view(
                'reports.current_stock.report',
                compact(
                    'report',
                    'stocks',
                    'totalCurrentStock',
                    'totalStockValue',
                    'categories',
                    'asOnDate'
                )
            );
        }
        if ($request->report_code == 'RPT-024') {
            $materialCode = $request->material_code;
            $fromDate = $request->ledger_from_date;
            $toDate = $request->ledger_to_date;
            $materials = DB::table('ingredient_masters')
                ->select(
                    'ingredient_code',
                    'ingredient_name'
                )
                ->orderBy('ingredient_name')
                ->get();
            $query = DB::table('ingredient_masters')
                ->select(
                    'ingredient_code as material_code',
                    'ingredient_name as material_name',
                    'category',
                    'base_uom',
                    'current_stock as qty',
                    'min_stock',
                    'purchase_cost',
                    'supplier',
                    'last_updated',
                    DB::raw("'CURRENT STOCK' as transaction_type"),
                    DB::raw("'OPENING' as reference_no"),
                    DB::raw("'Stock Snapshot' as remarks")
                );
            if (!empty($materialCode)) {
                $query->where(
                    'ingredient_code',
                    $materialCode
                );
            }
            $ledger = $query
                ->orderBy('ingredient_name')
                ->get();
            $runningBalance = 0;
            foreach ($ledger as $row) {
                $runningBalance += $row->qty;
                $row->balance_qty = $runningBalance;
            }
            return view(
                'reports.stock_ledger.report',
                compact(
                    'report',
                    'ledger',
                    'materials',
                    'materialCode',
                    'fromDate',
                    'toDate'
                )
            );
        }
        if ($request->report_code == 'RPT-041') {
            $fromDate = $request->discount_from_date;
            $toDate = $request->discount_to_date;
            $discountTypes = $request->discount_type;
            $query = DB::table('order_hd as oh')
                ->leftJoin(
                    'order_dt as od',
                    function ($join) {
                        $join->on('oh.tran_no', '=', 'od.tran_no')
                            ->on('oh.tran_date', '=', 'od.tran_date');
                    }
                )

                ->leftJoin('discount_master as dm', function ($join) {
                    $join->on(
                        DB::raw('dm.discount_id COLLATE utf8mb4_general_ci'),
                        '=',
                        DB::raw('oh.discount_code COLLATE utf8mb4_general_ci')
                    );
                })
                ->select(
                    'oh.tran_date as report_date',
                    'dm.type as discount_type',
                    'oh.discount_code',
                    'oh.discount_name',
                    DB::raw('COUNT(DISTINCT oh.invoice_no) as total_bills'),
                    DB::raw('SUM(DISTINCT oh.discount_amount) as total_discount'),
                    DB::raw('SUM(od.amount) as gross_sales'),
                    DB::raw('
                        (
                            SUM(DISTINCT oh.discount_amount)
                            /
                            NULLIF(SUM(od.amount),0)
                        ) * 100
                        as discount_percent
                    ')
                )
                ->whereBetween(
                    'oh.tran_date',
                    [
                        $fromDate,
                        $toDate
                    ]
                )
                ->whereNotNull('oh.discount_code');
            if (!empty($discountTypes)) {
                $query->whereIn(
                    'dm.type',
                    $discountTypes
                );
            }
            $discounts = $query
                ->groupBy(
                    'oh.tran_date',
                    'dm.type',
                    'oh.discount_code',
                    'oh.discount_name'
                )
                ->orderByDesc('total_discount')
                ->get();
            $grandTotalDiscount =
                $discounts->sum('total_discount');
            $discountTypeList = DB::table('discount_master')
                ->select('type')
                ->whereNotNull('type')
                ->where('type', '!=', '')
                ->distinct()
                ->orderBy('type')
                ->pluck('type');
            return view(
                'reports.discount_summary.report',
                compact(
                    'report',
                    'discounts',
                    'grandTotalDiscount',
                    'discountTypeList',
                    'fromDate',
                    'toDate'
                )
            );
        }
        if ($request->report_code == 'RPT-042') {
            $fromDate = $request->coupon_from_date;
            $toDate = $request->coupon_to_date;
            $couponCodes = $request->coupon_code;

            $query = DB::table('coupons as c')

                ->leftJoin(
                    'discount_master as dm',
                    DB::raw('CONVERT(dm.discount_id USING utf8mb4)'),
                    '=',
                    DB::raw('CONVERT(c.linked_discount_code USING utf8mb4)')
                )
                ->leftJoin(
                    'order_hd as oh',
                    DB::raw('CONVERT(oh.discount_code USING utf8mb4)'),
                    '=',
                    DB::raw('CONVERT(c.linked_discount_code USING utf8mb4)')
                )
                ->select(
                    'c.coupon_code',
                    'dm.type as discount_type',
                    'c.discount_name',
                    'c.usage_limit',
                    'c.used_count',
                    'c.remaining',
                    DB::raw('
                        CASE
                            WHEN c.usage_limit = 0
                            THEN 0
                            ELSE
                            (
                                c.used_count / c.usage_limit
                            ) * 100
                        END as redeem_percent
                    '),

                    DB::raw('COUNT(DISTINCT oh.invoice_no) as total_orders')
                )
                ->whereBetween(
                    'oh.tran_date',
                    [
                        $fromDate,
                        $toDate
                    ]
                );
            if (!empty($couponCodes)) {

                $query->whereIn(
                    'c.coupon_code',
                    $couponCodes
                );
            }
            $coupons = $query
                ->groupBy(
                    'c.coupon_code',
                    'dm.type',
                    'c.discount_name',
                    'c.usage_limit',
                    'c.used_count',
                    'c.remaining'
                )
                ->orderByDesc('c.used_count')
                ->get();
            return view(
                'reports.coupon_usage.report',
                compact(
                    'coupons',
                    'fromDate',
                    'toDate'
                )
            );
        }
        if ($request->report_code == 'RPT-047') {
            $businessDate =
                $request->business_date;
            $openingBalance = DB::table('order_hd')
                ->whereDate(
                    'tran_date',
                    '<',
                    $businessDate
                )
                ->where('payment_mode', 'C')
                ->sum('paid_amt');
            $cashSales = DB::table('order_hd')
                ->whereDate(
                    'tran_date',
                    $businessDate
                )
                ->where('payment_mode', 'C')
                ->sum('paid_amt');
            $otherReceipts = DB::table('order_hd')
                ->whereDate(
                    'tran_date',
                    $businessDate
                )
                ->sum('service_charge');
            $expenses = DB::table('order_hd')
                ->whereDate(
                    'tran_date',
                    $businessDate
                )
                ->sum('discount_amount');
            $bankDeposit = DB::table('order_hd')
                ->whereDate(
                    'tran_date',
                    $businessDate
                )
                ->whereIn(
                    'payment_mode',
                    ['U', 'D', 'O']
                )
                ->sum('paid_amt');
            $totalInflow =
                $openingBalance +
                $cashSales +
                $otherReceipts;
            $closingBalance =
                $totalInflow -
                $expenses -
                $bankDeposit;
            $drawerCount =
                $closingBalance;
            $difference =
                $drawerCount -
                $closingBalance;
            $differenceStatus =
                abs($difference) > 100
                    ? 'Mismatch'
                    : 'Matched';
            return view(
                'reports.cash_register.report',
                compact(
                    'businessDate',
                    'openingBalance',
                    'cashSales',
                    'otherReceipts',
                    'expenses',
                    'bankDeposit',
                    'totalInflow',
                    'closingBalance',
                    'drawerCount',
                    'difference',
                    'differenceStatus'
                )
            );
        }

        if ($request->report_code == 'RPT-015') {

            $fromDate = $request->from_date;
            $toDate   = $request->to_date;
        
            $topN = $request->top_n ?? 10;
        
            // Quantity / Revenue / Margin
            $rankBy = $request->rank_by ?? 'Quantity';
        
            $query = DB::table('order_dt as od')
                ->select(
                    'od.item_code',
        
                    // qty sold
                    DB::raw('SUM(IFNULL(od.item_qty,0)) as total_qty'),
        
                    // sales amount
                    DB::raw('SUM(IFNULL(od.amount,0)) as total_revenue'),
        
                    // margin (example)
                    DB::raw('SUM(IFNULL(od.amount,0) - IFNULL(od.item_gst,0)) as total_margin')
                )
                ->whereBetween(
                    'od.tran_date',
                    [$fromDate, $toDate]
                )
                ->groupBy('od.item_code');
        
            // sorting
            if ($rankBy == 'Revenue') {
        
                $query->orderByDesc('total_revenue');
        
            } elseif ($rankBy == 'Margin') {
        
                $query->orderByDesc('total_margin');
        
            } else {
        
                $query->orderByDesc('total_qty');
            }
        
            $reportData =
                $query
                ->limit($topN)
                ->get();
        
                return view(
                    'reports.top_selling_items.report',
                    compact(
                        'fromDate',
                        'toDate',
                        'topN',
                        'rankBy',
                        'reportData'
                    )
                );
        }

        if ($request->report_code == 'RPT-0004') {

            $fromDate =
                $request->from_date;
        
            $toDate =
                $request->to_date;
        
            $type =
                $request->order_type;
        
            $query =
                DB::table('order_hd')
                    ->select(
        
                        DB::raw("
                            CASE
                                WHEN order_mode = 'D'
                                    THEN 'Dine In'
        
                                WHEN order_mode = 'T'
                                    THEN 'Takeaway'
        
                                WHEN order_mode = 'O'
                                    THEN 'Online'
        
                                ELSE 'Other'
                            END
                            as order_type
                        "),
        
                        DB::raw(
                            'COUNT(*) as total_orders'
                        ),
        
                        DB::raw(
                            'SUM(IFNULL(net_amt,0)) as total_sales'
                        )
                    )
                    ->whereBetween(
                        'tran_date',
                        [$fromDate, $toDate]
                    );
        
            if (!empty($type)) {
        
                $query->where(
                    'order_mode',
                    $type
                );
            }
        
            $reportData =
                $query
                    ->groupBy('order_mode')
                    ->orderByDesc('total_sales')
                    ->get();
        
            $grandSales =
                $reportData->sum('total_sales');
        
            foreach ($reportData as $row) {
        
                $row->share_percent =
                    $grandSales > 0
                    ? round(
                        ($row->total_sales * 100)
                        / $grandSales,
                        2
                    )
                    : 0;
            }
        
            return view(
                'reports.sales_by_order_type.report',
                compact(
                    'reportData',
                    'fromDate',
                    'toDate',
                    'type'
                )
            );
        }

        if ($request->report_code == 'RPT-0005') {

            $fromDate = $request->from_date;
        
            $toDate = $request->to_date;
        
            $reportData =
                DB::table('order_hd')
                    ->select(
        
                        DB::raw("
                            HOUR(tran_time)
                            as hour_no
                        "),
        
                        DB::raw("
                            DATE_FORMAT(
                                MIN(tran_time),
                                '%h:00 %p'
                            ) as sale_hour
                        "),
        
                        DB::raw("
                            COUNT(*)
                            as total_orders
                        "),
        
                        DB::raw("
                            SUM(
                                IFNULL(net_amt,0)
                            ) as total_sales
                        "),
        
                        DB::raw("
                            ROUND(
                                AVG(
                                    IFNULL(net_amt,0)
                                ),
                                2
                            ) as avg_order
                        ")
                    )
        
                    ->whereBetween(
                        'tran_date',
                        [$fromDate, $toDate]
                    )
        
                    ->groupBy(
                        DB::raw('HOUR(tran_time)')
                    )
        
                    ->orderBy(
                        'hour_no'
                    )
        
                    ->get();
        
            return view(
                'reports.sales_by_hour.report',
                compact(
                    'reportData',
                    'fromDate',
                    'toDate'
                )
            );
        }
        abort(404, 'Report not configured.');
    }
}