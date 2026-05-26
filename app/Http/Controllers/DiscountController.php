<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountController extends Controller
{
    

public function getOffers(Request $request)
{
    $billAmount = (float) $request->bill_amount;
    $channel    = $request->channel;

    // bill amount 0 ya blank ho to offers mat dikhao
    if ($billAmount <= 0) {
        return response()->json([]);
    }

    $today = Carbon::today()->format('Y-m-d');

    $offers = DB::table('discount_master')
        ->where('status', 'Active')

        // bill amount jitna ya usse kam min_bill wale
        ->where('min_bill', '<=', $billAmount)

        // channel match ya ALL
        ->where(function ($q) use ($channel) {
            $q->where('channel', 'ALL')
              ->orWhere('channel', $channel);
        })

        // date valid
        ->whereDate('valid_from', '<=', $today)
        ->whereDate('valid_to', '>=', $today)

        ->orderBy('min_bill', 'desc')
        ->get();

    return response()->json($offers);
}
}