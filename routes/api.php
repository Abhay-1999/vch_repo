<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::any('payment/status', [OrderController::class, 'paymentStatus'])->name('payment.status');
