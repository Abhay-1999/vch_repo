<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function item_wise_form()
    {
        return view("reports.item_ws_form");
    }

    public function item_wise_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = DB::table('order_dt')
            ->select(
                'order_dt.item_code',
                'item_master.item_desc',
                'item_master.item_grpdesc',
                DB::raw('SUM(order_dt.item_qty) as total_qty'),
                DB::raw('SUM(order_dt.amount) as total_amount'),
                DB::raw('SUM(order_dt.item_gst) as total_gst')
            )
            ->leftJoin('item_master', 'order_dt.item_code', '=', 'item_master.item_code')
            ->whereBetween('order_dt.tran_date', [$startDate, $endDate])
            ->groupBy('order_dt.item_code', 'item_master.item_desc', 'item_master.item_grpdesc')
            ->orderBy('item_master.item_desc')
            ->get();

        return view("reports.item_ws_data", compact('startDate', 'endDate', 'data'));
    }

    public function bill_wise_form()
    {
        return view("reports.bill_ws_form");
    }

    public function bill_wise_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = DB::table('order_hd')
            ->select('order_hd.*')
            ->whereBetween('order_hd.tran_date',[$startDate, $endDate])
            ->orderBy('order_hd.tran_no')
            ->get();

        return view("reports.bill_ws_data",compact('startDate','endDate','data'));
    }

    public function pay_mode_form()
    {
        return view("reports.pay_mode_form");
    }

    public function pay_mode_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch and aggregate data
        $data = DB::table('order_hd')
            ->select('tran_date', 'payment_mode', DB::raw('SUM(net_amt) as total_net_amt'))
            ->whereBetween('tran_date', [$startDate, $endDate])
            ->groupBy('tran_date', 'payment_mode')
            ->orderBy('tran_date')
            ->get();
        
        $totals = [];
        foreach ($data as $d) {
            $date = date('d-m-Y', strtotime($d->tran_date));
            if (!isset($totals[$date])) {
                $totals[$date] = ['cash' => 0, 'upi' => 0];
            }
            if ($d->payment_mode == 'C') {
                $totals[$date]['cash'] += $d->total_net_amt;
            } elseif ($d->payment_mode == 'O') {
                $totals[$date]['upi'] += $d->total_net_amt;
            }
        }

            // echo "<pre>";print_r($data->toArray());die;

        return view("reports.pay_mode_data",compact('startDate','endDate','totals'));
    }
}
