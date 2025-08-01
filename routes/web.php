<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RazorpayController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CashfreeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerMasterController;


Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login/submitt', [AdminAuthController::class, 'submit']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/ordersp', [OrderController::class, 'indexp'])->name('orders.indexp');
    Route::get('/delivered', [OrderController::class, 'delivered'])->name('orders.delivered');
    Route::get('/items', [OrderController::class, 'items'])->name('items');
    Route::get('/create-order', [OrderController::class, 'create_order'])->name('create.order');

    Route::post('/update-item-status', [OrderController::class, 'updateStatus'])->name('update.item.status');

    Route::get('/order-detail/{id}', [OrderController::class, 'OrderDetail'])->name('order.detail');
    Route::post('/orders/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::post('/orders/makePayment', [OrderController::class, 'makePayment'])->name('orders.makePayment');
    Route::post('/orders/flag', [OrderController::class, 'updateFlag'])->name('orders.flag');
    Route::post('/orders/hold', [OrderController::class, 'hold'])->name('orders.hold');

    // customer master

    Route::get('/cust-mast', [CustomerMasterController::class, 'cust_mast_form'])->name('cust_mast');
    Route::post('/cust-save', [CustomerMasterController::class, 'store'])->name('cust_save');
    Route::get('/cust-edit/{id}', [CustomerMasterController::class, 'edit']);
    Route::get('/cust-delete/{id}', [CustomerMasterController::class, 'destroy'])->name('cust.delete');

    //discount setting 
    Route::get('/dis-set', [CustomerMasterController::class, 'disc_set_form'])->name('disc_set_form');
    Route::post('/dis-set-update', [CustomerMasterController::class, 'updateDiscSet'])->name('disc_update');

    //reports 
    //item wise
    Route::get("item-form",[ReportController::class,'item_wise_form'])->name('item_ws_form');
    Route::post("item-data",[ReportController::class,'item_wise_data'])->name('item_ws_data');

    //bill wise
    Route::get("bill-form",[ReportController::class,'bill_wise_form'])->name('bill_ws_form');
    Route::post("bill-data",[ReportController::class,'bill_wise_data'])->name('bill_ws_data');

    //mode wise payment
    Route::get('dt-sale-form',[ReportController::class,'pay_mode_form'])->name('mode_pay_form');
    Route::post('dt-sale-data',[ReportController::class,'pay_mode_data'])->name('mode_pay_data');
    
    //total sale
    Route::get('/sale-form',[ReportController::class,'total_sale_form'])->name('sale_form');
    Route::post('/sale-data',[ReportController::class,'total_sale_data'])->name('tot_sale_data');

    Route::get('/orders/refresh', [OrderController::class, 'refresh'])->name('orders.refresh');
    Route::get('/ordersp/refresh', [OrderController::class, 'refreshp'])->name('ordersp.refresh');
    Route::get('/ordersp/refreshdelivered', [OrderController::class, 'refreshdelivered'])->name('orders.refreshdelivered');

    Route::get('dashboard', function () {
        return view('auth.admin.dashboard'); // Ensure this view exists
    })->middleware('auth:admin'); 
});

Route::prefix('user')->group(function () {
    Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
    Route::post ('login', [UserAuthController::class, 'login']);
    Route::post('logout', [UserAuthController::class, 'logout'])->name('user.logout');
});


Route::post('/payment/dummy', [ItemController::class, 'payment_page'])->name('payment.dummy');

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::post('/add-to-cart/{id}', [ItemController::class, 'addToCart'])->name('items.addToCart');
Route::get('/cart', [ItemController::class, 'cart'])->name('items.cart');
Route::get('/checkout', [ItemController::class, 'checkout'])->name('items.checkout');
Route::post('/add-to-cart-item', [ItemController::class, 'addToCartitem'])->name('items.addToCartitem');
Route::post('/order-save', [ItemController::class, 'save'])->name('order.save');

Route::get('/all-items', [ItemController::class, 'all']);


Route::post('/send-otp', [ItemController::class, 'sendOtp'])->name('send.otp');
Route::post('/otp-sbt', [ItemController::class, 'otpSubmit'])->name('otp.sbt');
Route::post('/item-filter', [ItemController::class, 'itemFilter'])->name('item.filter');
Route::get('/terms-condition', [ItemController::class, 'terms'])->name('terms');

Route::post('/remove-from-cart', [ItemController::class, 'removeFromCart'])->name('items.removeFromCart');

Route::get('/generate-qr-code', [QRCodeController::class, 'generateQRCode']);
Route::get('/show-qr-code', [QRCodeController::class, 'showQRCode']);
// Route::get('/scan', [QrScanController::class, 'handleScan']);


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/razorpay/checkout', [RazorpayController::class, 'checkout']);
// web.php
// Route::get('/pay', [RazorpayController::class, 'createOrder']);


// routes/web.php

// Route::get('/pay', [CashfreeController::class, 'showPayPage'])->name('cashfree.pay');
// Route::post('/pay/submit', [CashfreeController::class, 'createOrder'])->name('cashfree.create');
// Route::get('/pay/success', [CashfreeController::class, 'paymentSuccess'])->name('cashfree.success');
// Route::post('/webhook/cashfree', [CashfreeController::class, 'handleWebhook'])->name('cashfree.webhook');


Route::post('/order/item-update', [OrderController::class, 'updateOrderItem'])->name('order.item.update');
Route::post('/order/complete', [OrderController::class, 'markOrderComplete'])->name('order.complete');


Route::post('/change/paymentMode', [OrderController::class, 'UpdatePaymode'])->name('change.paymentMode');

Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');

Route::get('/change-order', [OrderController::class, 'ChangeOrder'])->name('change.order');


Route::post('/print-content', [OrderController::class, 'printContent'])->name('print.content');


// Route::get('/pay', [OrderController::class, 'initiateHDFCPayment']);
// Route::any('/payment/response', function(Request $request) {
//     // Handle success or failure callback here
//     return response()->json($request->all());
// });



Route::post('/pay', [OrderController::class, 'initiatePayment'])->name('initiate.payment');
// Route::any('/payment/status', [OrderController::class, 'paymentStatus'])->name('payment.status.check');


Route::get('/order-status/{order_id}', [OrderController::class, 'checkOrderStatus'])->name('order.status.payment');
