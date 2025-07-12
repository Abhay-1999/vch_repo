<?php
namespace App\Http\Controllers;

use Razorpay\Api\Api;
use Illuminate\Http\Request;

class RazorpayController extends Controller
{
    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt'         => 'rcptid_11',
            'amount'          => 10000, // amount in paise = ₹100
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $api->order->create($orderData);
        $order_id = $razorpayOrder['id'];

        return view('razorpay.checkout', [
            'order_id' => $order_id,
            'amount' => 10000,
            'razorpay_key' => env('RAZORPAY_KEY')
        ]);
    }
}
