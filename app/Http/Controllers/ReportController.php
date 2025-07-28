<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        $group_code = Session::get('group_code');
        $rest_code = Session::get('rest_code');

        $rest_data = DB::table('chain_master')
            ->where('group_code', $group_code)
            ->where('rest_code', $rest_code)
            ->first();

        $data = DB::table('order_dt')
            ->select('order_dt.item_code','item_master.item_desc','item_master.item_grpdesc',
                DB::raw('SUM(order_dt.item_qty) as total_qty'),
                DB::raw('SUM(order_dt.item_gm/1000) as total_gm_qty'),
                DB::raw('SUM(order_dt.amount) as total_amount'),
                DB::raw('SUM(order_dt.item_gst) as total_gst')
            )
            ->leftJoin('item_master', 'order_dt.item_code', '=', 'item_master.item_code')
            ->whereBetween('order_dt.tran_date', [$startDate, $endDate])
            ->where('order_dt.customise_flag','!=','H')
            ->groupBy('order_dt.item_code', 'item_master.item_desc', 'item_master.item_grpdesc',)
            ->orderBy('total_qty', 'desc')
            ->orderBy('item_desc')
            ->get();

        return view("reports.item_ws_data", compact('startDate', 'endDate', 'data','rest_data'));
    }

    public function bill_wise_form()
    {
        return view("reports.bill_ws_form");
    }

    public function bill_wise_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $paymentMode = $request->input('payment_mode');
        $group_code = Session::get('group_code');
        $rest_code = Session::get('rest_code');

        $rest_data = DB::table('chain_master')
            ->where('group_code', $group_code)
            ->where('rest_code', $rest_code)
            ->first();

        $query = DB::table('order_hd')
            ->select('order_hd.*')
            ->whereBetween('order_hd.tran_date', [$startDate, $endDate])
            ->where('order_hd.status_trans', 'success');

        if (!empty($paymentMode)) {
            $query->where('order_hd.payment_mode', $paymentMode);
        }

        $data = $query->orderBy('order_hd.tran_date')
            ->orderBy('order_hd.invoice_no')
            ->get()
            ->map(function ($d) {
                $inclusiveAmount = $d->paid_amt;
                $baseAmount = $inclusiveAmount / 1.05; // Remove 5% GST
                $cgst = $baseAmount * 0.025;
                $sgst = $baseAmount * 0.025;
                $totalGst = $cgst + $sgst;

                $d->base_amt = $baseAmount;
                $d->cgst_amt = $cgst;
                $d->sgst_amt = $sgst;
                $d->total_gst = $totalGst;
                $d->net_amt_incl_gst = $inclusiveAmount;

                return $d;
            });

        return view("reports.bill_ws_data", compact('startDate', 'endDate', 'data','rest_data'));
    }

    public function pay_mode_form()
    {
        return view("reports.pay_mode_form");
    }

    public function pay_mode_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $group_code = Session::get('group_code');
        $rest_code = Session::get('rest_code');

        $rest_data = DB::table('chain_master')
            ->where('group_code', $group_code)
            ->where('rest_code', $rest_code)
            ->first();

        $data = DB::table('order_hd')
            ->select('tran_date', 'payment_mode', DB::raw('SUM(paid_amt) as total_net_amt'))
            ->whereBetween('tran_date', [$startDate, $endDate])
            ->where('order_hd.flag','!=','H')
            ->groupBy('tran_date', 'payment_mode')
            ->orderBy('tran_date')
            ->get();
        
        $totals = [];
        foreach ($data as $d) {
            $date = date('d-m-Y', strtotime($d->tran_date));
            if (!isset($totals[$date])) {
                $totals[$date] = ['cash' => 0, 'UPI' => 0, 'Online' => 0, 'Zomato' => 0, 'Swiggy' => 0 ];
            }
            if ($d->payment_mode == 'C') {
                $totals[$date]['cash'] += $d->total_net_amt;
            } elseif ($d->payment_mode == 'O') {
                $totals[$date]['Online'] += $d->total_net_amt;
            }elseif ($d->payment_mode == 'U') {
                $totals[$date]['UPI'] += $d->total_net_amt;
            }elseif ($d->payment_mode == 'Z') {
                $totals[$date]['Zomato'] += $d->total_net_amt;
            }elseif ($d->payment_mode == 'S') {
                $totals[$date]['Swiggy'] += $d->total_net_amt;
            }
        }

        // echo "<pre>";print_r($data->toArray());die;

        return view("reports.pay_mode_data",compact('startDate','endDate','totals','rest_data'));
    }

    public function bill_item_wise_form()
    {
        return view("reports.bill_item_form");
    }

    public function bill_item_wise_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = DB::table('order_dt')
            ->select(
                'order_dt.*',
                'order_hd.payment_mode',
                'order_hd.invoice_no',
                'item_master.item_desc'
            )
            ->leftJoin('order_hd', function($join) {
                $join->on('order_dt.tran_no', '=', 'order_hd.tran_no')
                    ->on('order_dt.tran_date', '=', 'order_hd.tran_date');
            })
            ->leftJoin('item_master', 'order_dt.item_code', '=', 'item_master.item_code')
            ->whereBetween('order_dt.tran_date', [$startDate, $endDate])
            ->where('order_hd.flag','!=','H')
            ->orderBy('order_hd.tran_date')
            ->orderBy('order_hd.invoice_no')
            ->get();

        return view("reports.bill_item_data",compact('startDate','endDate','data'));
    }

    public function total_sale_form()
    {
        return view("reports.tot_sale_form");
    }

    public function total_sale_data(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $group_code = Session::get('group_code');
        $rest_code = Session::get('rest_code');

        $rest_data = DB::table('chain_master')
            ->where('group_code', $group_code)
            ->where('rest_code', $rest_code)
            ->first();

        $data = DB::table('order_hd')
            ->select(DB::raw('SUM(paid_amt) as paid_amt'))
            ->whereBetween('tran_date', [$startDate, $endDate])
            ->where('status_trans','success')
            ->first();

        if ($data && $data->paid_amt !== null) {
            $inclusiveAmount = $data->paid_amt;
            $baseAmount = $inclusiveAmount / 1.05; // Remove 5% GST
            $cgst = $baseAmount * 0.025;
            $sgst = $baseAmount * 0.025;
            $totalGst = $cgst + $sgst;

            $data->base_amt = round($baseAmount, 2);
            $data->cgst_amt = round($cgst, 2);
            $data->sgst_amt = round($sgst, 2);
            $data->total_gst = round($totalGst, 2);
            $data->net_amt_incl_gst = round($inclusiveAmount, 2);
        }

        return view("reports.tot_sale_data",compact('startDate','endDate','data','rest_data'));
    }
}