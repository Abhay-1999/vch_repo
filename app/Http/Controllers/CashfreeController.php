<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CashfreeController extends Controller
{
    public function showPayPage()
    {
        return view('cashfree.pay');
    }

    public function createOrder(Request $request)
    {
        $orderId = 'ORD' . time();
        $amount = 100.00;

        $response = Http::withHeaders([
            'x-client-id' => env('CASHFREE_APP_ID'),
            'x-client-secret' => env('CASHFREE_SECRET_KEY'),
            'x-api-version' => '2022-01-01',
            'Content-Type' => 'application/json',
        ])->post(env('CASHFREE_BASE_URL') . '/orders', [
            'order_id' => $orderId,
            'order_amount' => $amount,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'CUST001',
                'customer_email' => 'test@example.com',
                'customer_phone' => '9876543210',
            ],
            'order_meta' => [
                'return_url' => route('cashfree.success') . '?order_id={order_id}',
                'notify_url' => route('cashfree.webhook'),
            ]
        ]);

        // echo $response;die;

        $order = $response->json();

        if (!empty($order['order_token'])) {
            return response()->json([
                'success' => true,
                'order_token' => $order['order_token']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $order['message'] ?? 'Error creating order'
            ]);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        return view('cashfree.receipt', compact('orderId'));
    }

    public function handleWebhook(Request $request)
    {
        \Log::info('Cashfree Webhook:', $request->all());
        return response('Webhook received', 200);
    }
}
