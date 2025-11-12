<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;


Route::match(['get','post'],'/upi/callback', [OrderController::class,'paymentCallback'])->name('upi.callback');

Route::get('payment/status', [OrderController::class, 'paymentStatus'])->name('payment.status');
