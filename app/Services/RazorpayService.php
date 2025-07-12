<?php

namespace App\Services;

use Razorpay\Api\Api;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    }

    public function createOrder($amount, $receipt, $transfers)
    {
        return $this->api->order->create([
            'receipt' => $receipt,
            'amount' => $amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1,
            'transfers' => $transfers // 👈 Split transfers
        ]);
    }
}
