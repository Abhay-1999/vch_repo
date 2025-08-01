<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\PdfToImage\Pdf as PdfToImage;


class ItemController extends Controller
{

    public function all()
    {
        $items = Item::select('item_desc','item_code','rest_code','item_rate','item_status','start_time','end_time')->orderBy('item_desc')->get();


    //    echo"<pre>";print_r($items->toArray());die;
        return response()->json(['items' => $items], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    
    public function index(Request $request)
    {
        // echo"a";die;
        if (!request()->hasCookie(config('session.cookie'))) {
            session()->forget('cart');
        }
        // session()->forget('cart');
      //  echo"<pre>";print_r($request->all());die;
        $encoded = $request->query('data');

        // if (!$encoded) {
        //     return response()->json(['error' => 'Missing data'], 400);
        // }

        // Decode base64
        $json = base64_decode($encoded);

        // Decode JSON into associative array
        $data = json_decode($json, true);

        // if (!is_array($data)) {
        //     return response()->json(['error' => 'Invalid format'], 400);
        // }

        // Access the keys
        $group_code = $data['group_code'] ?? null;
        $rest_code = $data['rest_code'] ?? null;

       // echo $group_code.'-'.$rest_code;die;

        $rest_code = '01';
        $group_code = '01';

        Session::put('group_code',$group_code);
        Session::put('rest_code',$rest_code);

        // $items = Item::where('group_code',$group_code)->where('item_status','A')->where('rest_code',$rest_code)->get();

        // $now = now()->format('H:i:s');
        $now = now('Asia/Kolkata')->format('H:i:s');

        $items = DB::table('item_master')
        ->where('group_code',$group_code)
        ->where('item_status','A')
        ->where('rest_code',$rest_code)
        ->where(function ($query) use ($now) {
        // Time-based items
        $query->where(function ($q) use ($now) {
            $q->whereRaw('start_time <= end_time')
              ->where('start_time', '<=', $now)
              ->where('end_time', '>=', $now);
        })
        // Midnight wrap items
        ->orWhere(function ($q) use ($now) {
            $q->whereRaw('start_time > end_time')
              ->where(function ($q2) use ($now) {
                  $q2->where('start_time', '<=', $now)
                     ->orWhere('end_time', '>=', $now);
              });
        })
        // Always visible items
        ->orWhere(function ($q) {
            $q->whereNull('start_time')->whereNull('end_time');
        });
    })
    ->get();


        $item_grpcodes = Item::select('item_grpcode','item_grpdesc')->where('group_code',$group_code)->where('item_status','A')->where('rest_code',$rest_code)->groupBy('item_grpcode','item_grpdesc')->get();
        
    // echo"<pre>";print_r($item_grpcodes->toArray());die;
        return view('items.index', compact('items','item_grpcodes'));
    }

    public function addToCart(Request $request, $id)
    {
        // echo"<pre>";print_r($request->all());die;
        $item = Item::where('item_code',$id)->where('item_status','A')->where('item_grpcode',$request->item_grpcode)->first();
        if(!$item){
            return response()->json([
                'success' => false,
                'message' => 'Item out of Stock!'
            ]);
        }

        $now = now('Asia/Kolkata')->format('H:i:s');

    $itemCheck = Item::where('item_code', $id)
    ->where('item_status', 'A')
    ->where('item_grpcode', $request->item_grpcode)
    ->where(function ($query) use ($now) {
        $query
            // Case 1: Normal time range (start <= end)
            ->where(function ($q) use ($now) {
                $q->whereRaw('start_time <= end_time')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            })
            // Case 2: Midnight-wrap range (start > end)
            ->orWhere(function ($q) use ($now) {
                $q->whereRaw('start_time > end_time')
                  ->where(function ($q2) use ($now) {
                      $q2->where('start_time', '<=', $now)
                         ->orWhere('end_time', '>=', $now);
                  });
            })
            // Case 3: No time restriction (null start and end)
            ->orWhere(function ($q) {
                $q->whereNull('start_time')
                  ->whereNull('end_time');
            });
    })
    ->first();

        if (!$itemCheck) {
            return response()->json([
                'success' => true,
                'message' => 'Item is not in stock!'
            ]);
        }

        


        $cart = session()->get('cart', []);
        

        if (isset($cart[$id])) {
            // $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "item_code" => $item->item_code,
                "item_grpcode" => $item->item_grpcode,
                "name" => $item->item_desc,
                "price" => $item->item_rate,
                "quantity" => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart!'
        ]);

        // return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function cart()
    {
        return view('items.cart');
    }

    public function payment_page(Request $request){

        $total_amount = $request->total_amount;
        $paymode_mode = $request->paymode_mode;
        $confirm_order = $request->confirm_order;
        
        Session::put('total_amount',$total_amount);
        Session::put('paymode_mode',$paymode_mode);
        Session::put('confirm_order',$confirm_order);

        if($paymode_mode == 'C'){
            return redirect()->route('items.checkout');

        }else{
            return view('payment-gateway-dummy',compact('total_amount','paymode_mode'));

        }

    }

    public function checkout()
    { //echo"a";die;
        // Handle checkout logic here
       $carts = session()->get('cart');
  
        $total_amount = Session::get('total_amount');
        // echo"<pre>";print_r($total_amount);die;
        $amount = $total_amount;
        $group_code = '01';
        $rest_code = '01';
        $paymode_mode = 'O';
        $confirm_order = 'Y';;

        $taxes = $itemWiseAmt = 0; 

        foreach($carts as $cart){

            $item_gst = DB::table('item_master')->where('group_code',$group_code)->where('rest_code',$rest_code)
            ->where('item_code',$cart['item_code'])->value('item_gst');

             $itemWiseAmt += $cart['quantity']*$cart['price'];
             $taxes += ($item_gst*$itemWiseAmt/100);

        }

        $convin_amt = 0;

        $convin_amt_gst = 0;

        $final_conv =  $convin_amt + $convin_amt_gst;

        $paid_amt = $amount;

        $service_charge = 0;
        
        $sgst_service = 0;

        $cgst = 0;

        $gross_amt = $cgst + $cgst + $amount;
        $transactionNumber = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        if($paymode_mode=='C'){
            $status = 'pending';
        }else{
            $status = 'success';
        }
        $mobile =  Session::get('phone');

        $trans_no =  DB::table('order_hd')->where(['rest_code' => $rest_code,'tran_date'=>date('Y-m-d')])->orderby('tran_no','desc')->value('tran_no');

        if($trans_no){
            $trans_no = $trans_no + 1;
        }else{
            $trans_no = 1;
        }

        $order_hd = [
            'group_code' => $group_code,
            'tran_no' => $trans_no,
            'rest_code' => $rest_code,
            'net_amt' => $amount, 
            'cgst_amt' => $cgst, 
            'sgst_amt' => $cgst, 
            'gross_amt' => $amount, 
            'paid_amt' => round($amount), 
            'order_id' =>rand(1111,9999), 
            'cust_mobile' =>$mobile, 
            'service_charge'=>$convin_amt, 
            'service_cgst' =>$convin_amt_gst/2, 
            'service_sgst' =>$convin_amt_gst/2, 
            'email' =>'test@gmail.com', 
            'status_trans' =>$status, 
            'flag' => 'S', 
            'confirm_order' => $confirm_order, 
            'transaction_no' =>$transactionNumber, 
            'payment_mode' =>$paymode_mode, 
        ];

        DB::table('order_hd')->insertGetId($order_hd);

    //    echo $trans_no;die;

       DB::table('order_hd')
        ->where('tran_no', $trans_no)
        ->where('tran_date',date('Y-m-d'))
        ->update(['invoice_no' => $trans_no]);

        foreach($carts as $cart){
            // echo"<pre>";print_r($cart);die;
            $item_gst = DB::table('item_master')->where('group_code',$group_code)->where('rest_code',$rest_code)
            ->where('item_code',$cart['item_code'])->value('item_gst');

            $item_amt = $cart['quantity']*$cart['price'];

            // $item_gst_amt =  ($item_amt*$item_gst/100);

            
            $reverseamt = $this->reverseGST($item_amt,$item_gst);

            $item_amt_real = $reverseamt['base'];
            $item_gst_amt = $reverseamt['gst'];


            DB::table('order_dt')->insert([
                'group_code' => $group_code,
                'rest_code' => $rest_code,
                'tran_no' => $trans_no,
                'item_code' => $cart['item_code'],
                'item_qty' => $cart['quantity'],
                'customise_flag' => 'S',
                'amount' =>$item_amt_real,
                'item_gst' =>$item_gst_amt,
            ]);
        }


        Session::put('tran_no', $trans_no);
        
        // $trans_no = 1;

        $hd_data =   DB::table('order_hd')->where('tran_no',$trans_no)->where('tran_date',date('Y-m-d'))->where('status_trans',$status)->first();

        $rest_data =  DB::table('chain_master')->where('group_code',$group_code)->where('rest_code',$rest_code)->first();

        $dt_data =   DB::table('order_dt')->select('order_dt.*','item_master.item_desc','item_master.item_gst as igst','item_master.item_rate')
        ->join('item_master','order_dt.item_code','=','item_master.item_code')
        ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
        ->where('order_hd.tran_no',$trans_no)
        ->where('order_dt.tran_date',date('Y-m-d'))
        ->where('order_hd.tran_date',date('Y-m-d'))
        ->where('order_hd.status_trans',$status)
        ->where('order_dt.tran_no',$trans_no)
        ->get();

      // $this->generateBillImage($trans_no,$mobile);

         session()->forget('cart');


        
        
         return view('items.bill', compact('dt_data', 'hd_data', 'rest_data'));



    }

    public function addToCartitem(Request $request)
    {
        // echo"<pre>";print_r($request->all());die;
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');
        $id = $request->id;

        $item = Item::where('item_code',$id)->where('item_status','A')->first();

        if(!$item){
            return response()->json([
                'success' => false,
                'message' => 'Item out of Stock!'
            ]);
        }

        $now = now('Asia/Kolkata')->format('H:i:s');

    $itemCheck = Item::where('item_code', $id)
    ->where('item_status', 'A')
    ->where(function ($query) use ($now) {
        $query
            // Case 1: Normal time range (start <= end)
            ->where(function ($q) use ($now) {
                $q->whereRaw('start_time <= end_time')
                  ->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
            })
            // Case 2: Midnight-wrap range (start > end)
            ->orWhere(function ($q) use ($now) {
                $q->whereRaw('start_time > end_time')
                  ->where(function ($q2) use ($now) {
                      $q2->where('start_time', '<=', $now)
                         ->orWhere('end_time', '>=', $now);
                  });
            })
            // Case 3: No time restriction (null start and end)
            ->orWhere(function ($q) {
                $q->whereNull('start_time')
                  ->whereNull('end_time');
            });
    })
    ->first();

        if (!$itemCheck) {
            return response()->json([
                'success' => true,
                'message' => 'Item is not in stock!'
            ]);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
        } else {
            $cart[$id] = [
                "item_code" => $item->item_code,
                "item_grpcode" => $item->item_grpcode,
                "name" => $item->item_desc,
                "price" => $item->item_rate,
                "quantity" => $quantity

            ];
        }
        $totalQuantity = array_sum(array_column($cart, 'quantity'));

        session()->put('cart', $cart);
        return response()->json(['quantity' => $cart[$id]['quantity'],'total_quantity' => $totalQuantity, 'success' => true,
        'message' => 'Item added to cart']);
    }
    
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;
    
        if (isset($cart[$id])) {
            if ($request->has('quantity')) {
                $quantity = (int) $request->quantity;
    
                if ($quantity < 1) {
                    unset($cart[$id]); // Remove item if quantity < 1
                } else {
                    $cart[$id]['quantity'] = $quantity; // Update quantity
                }
            } else {
                unset($cart[$id]); // Remove item directly
            }
    
            session()->put('cart', $cart); // Save updated cart
    
            // Calculate total quantity in cart
            $totalQuantity = array_sum(array_column($cart, 'quantity'));
    
            // Check if the item still exists in the cart
            if (isset($cart[$id])) {
                return response()->json([
                    'quantity' => $cart[$id]['quantity'],
                    'total_quantity' => $totalQuantity,
                ]);
            } else {
                return response()->json([
                    'message' => 'Item removed successfully.',
                    'total_quantity' => $totalQuantity,
                ]);
            }
        }
    
        return response()->json(['message' => 'Item not found in cart.'], 404);
    }
    





    function sendOtp(Request $request)
    {
        $mobile = '91'.$request->mobile;
        $otp = rand(1111,9999);
        Session::put('phone',$mobile);

        $templateId = env('EntertemplateID1'); // Replace with your actual template ID
        $authKey = env('AuthKeySms'); // Replace with your actual auth key

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://control.msg91.com/api/v5/otp?template_id={$templateId}&mobile={$mobile}&authkey={$authKey}", [
            'var1' => $otp,
        ]);

        if ($response->failed()) {
            // Log the error details
            \Log::error('API Request Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json(['error' => 'cURL Error: ' . $response->body()], 500);
        }

        return response()->json($response->json());
    }


    public function verifyOtp($mobile,$otp)
    {
        // Prepare the URL and headers
        $url = "https://control.msg91.com/api/v5/otp/verify?otp={$otp}&mobile={$mobile}";
        $headers = [
            'authkey' => env('AuthKeySms'),
        ];

        // Make the GET request using Laravel's HTTP client
        $response = Http::withHeaders($headers)->get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        }else {
            return response()->json(['error' => 'cURL Error: ' . $response->body()], $response->status());
        }

        // Return the response
        return response()->json($response->json());
    }


    public function otpSubmit(Request $request)
    {

        
        $mobile =  Session::get('phone');

        //  echo $request->otp;die;

            $otpVerified = $this->verifyOtp($mobile,$request->otp);

            if ($otpVerified instanceof \Illuminate\Http\JsonResponse) {
            $otpVerifi = $otpVerified->getData(true); // Get the data as an array
            // Check if the 'original' key exists and extract the message
            if($otpVerifi['type']=='success'){
                return response()->json([
                    'success' => 1,
                    'message' => $otpVerifi['message'],

                ]);
                }else{
                    return response()->json([
                        'success' =>2,
                        'message' => $otpVerifi['message'],
                    ]);
                }
            // Now you can use the $message variable as needed
            
        }   
        
    }

    public function itemFilter(Request $request)
    {
        $query = Item::query();

        if ($request->filled('item_desc')) {
            $query->where('item_desc', 'like', '%' . $request->item_desc . '%');
        }

        if ($request->filled('veg_nonveg')) {
            $query->where('veg_nonveg', $request->veg_nonveg);
        }

        if ($request->filled('item_grpdesc')) {
            $query->where('item_grpcode',$request->item_grpdesc);
        }

        

        $group_code = '01';
        $rest_code = '01';

        
        $items = $query->where('group_code',$group_code)->where('rest_code',$rest_code)->get();

        if ($request->ajax()) {
            $view = view('items._items', compact('items'))->render();
            return response()->json(['html' => $view]);
        }

        return view('items.index', compact('items'));
    }

    function terms(){
        
        return view('items.terms');
    }

    public function save(Request $request)
    {

        $cart = $request->cart; // From POST
        // echo"<pre>";print_r($cart);die;
        $paymode_mode = $request->paymode; // C / Z / S / O
        $order_id = $request->order_id; // C / Z / S / O
        $mobile = $request->mobile; // C / Z / S / O
        $finalAmt = $request->ft; // C / Z / S / O
        $discount = $request->dsc; // C / Z / S / O

        $group_code = '01';
        $rest_code ='01';
        $confirm_order = 'Y';
       /// $mobile = Session::get('phone', '');

        $amount = 0;
        $itemWiseAmt = 0;
        $taxes = 0;

        foreach ($cart as $item) {
            $item_gst = DB::table('item_master')
                ->where('group_code', $group_code)
                ->where('rest_code', $rest_code)
                ->where('item_code', $item['id'])
                ->value('item_gst');
            if(@$item['qty']){
                $line_amt = @$item['qty'] * $item['price'];
            }else{
                $line_amt = $item['amount'];
            }
            
            $itemWiseAmt += $line_amt;
            $taxes += ($item_gst * $itemWiseAmt / 100);


        }

        $convin_amt = 0;
        $convin_amt_gst = 0;
        $final_conv = 0;

        $amount = $itemWiseAmt;
        if($finalAmt){
            $paid_amt = $finalAmt;
        }else{
            $paid_amt = $amount;
        }
      
        $service_charge = 0;
        $cgst = 0;
        $gross_amt = $cgst + $cgst + $amount;

        $transactionNumber = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        $status = 'success';

        $trans_no =  DB::table('order_hd')->where(['rest_code' => $rest_code,'tran_date'=>date('Y-m-d')])->orderby('tran_no','desc')->value('tran_no');

        if($trans_no){
            $trans_no = $trans_no + 1;
        }else{
            $trans_no = 1;
        }

    //    echo $paid_amt;die;


        $order_hd = [
            'group_code' => $group_code,
            'rest_code' => $rest_code,
            'tran_no' => $trans_no,
            'discount' => $discount,
            'net_amt' => $amount,
            'cgst_amt' => $cgst,
            'sgst_amt' => $cgst,
            'gross_amt' => $amount,
            'paid_amt' => $paid_amt,
            'order_id' =>$order_id,
            'cust_mobile' => $mobile,
            'service_charge' => $convin_amt,
            'service_cgst' => $convin_amt_gst / 2,
            'service_sgst' => $convin_amt_gst / 2,
            'email' => 'test@gmail.com',
            'status_trans' => $status,
            'flag' => 'S',
            'confirm_order' => $confirm_order,
            'transaction_no' => $transactionNumber,
            'payment_mode' => $paymode_mode,
        ];

       DB::table('order_hd')->insertGetId($order_hd);


        foreach ($cart as $item) {
            $item_gst = DB::table('item_master')
                ->where('group_code', $group_code)
                ->where('rest_code', $rest_code)
                ->where('item_code', $item['id'])
                ->value('item_gst');
            if(@$item['qty']){
                $item_amt = @$item['qty'] * $item['price'];
            }else{
                $item_amt = $item['amount'];
            }
          
            // $item_gst_amt = ($item_amt * $item_gst / 100);
            if(@$item['qty']){
                $reverseamt = $this->reverseGST($item_amt,$item_gst);
            }else{
                $reverseamt = $this->reverseGST($item_amt,$item_gst);
            }

            $item_amt_real = $reverseamt['base'];
            $item_gst_amt = $reverseamt['gst'];

            DB::table('order_dt')->insert([
                'group_code' => $group_code,
                'rest_code' => $rest_code,
                'tran_no' => $trans_no,
                'item_code' => $item['id'],
                'item_qty' => @$item['qty'],
                'item_gm' => round(@$item['grams']),
                'customise_flag' => 'S',
                'amount' => $item_amt_real,
                'item_gst' => $item_gst_amt,
            ]);
        }


        $hd_data =   DB::table('order_hd')->where('tran_no',$trans_no)->where('tran_date',date('Y-m-d'))->where('status_trans','success')->first();

        $rest_data =  DB::table('chain_master')->where('group_code',$group_code)->where('rest_code',$rest_code)->first();

        $dt_data =   DB::table('order_dt')->select('order_dt.*','item_master.item_desc','item_master.item_gst as igst')
        ->join('item_master','order_dt.item_code','=','item_master.item_code')
        ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
        ->where('order_hd.tran_no',$trans_no)
        ->where('order_hd.tran_date',date('Y-m-d'))
        ->where('order_dt.tran_date',date('Y-m-d'))

        ->where('order_hd.status_trans','success')
        ->where('order_dt.tran_no',$trans_no)
        ->get();

      // $this->generateBillImage($trans_no,$mobile);

    //   echo"<pre>";print_r($hd_data);die;


        
        
        //  $billHtml = view('items.bill', compact('dt_data', 'hd_data', 'rest_data'))->render();

         session()->forget('cart');
        return response()->json(['success' => true, 'order_id' => $trans_no]);
    }


    function reverseGST($totalAmount, $gstRate) {
        $baseAmount = ($totalAmount * 100) / (100 + $gstRate);
        $gstAmount = $totalAmount - $baseAmount;
        return [
            'base' => round($baseAmount, 2),
            'gst'  => round($gstAmount, 2)
        ];
    }



public function generateBillImage($trans_no,$toPhoneNumber)
{

    $total_amaount = Session::get('total_amaount');
    $amount = $total_amaount;
    $group_code = '01';
    $rest_code = '01';

    $hd_data =   DB::table('order_hd')->where('tran_no',$trans_no)->where('tran_date',date('Y-m-d'))->where('status_trans','success')->first();

    $rest_data =  DB::table('chain_master')->where('group_code',$group_code)->where('rest_code',$rest_code)->first();

    $dt_data =   DB::table('order_dt')->select('order_dt.*','item_master.item_desc','item_master.item_gst as igst')
    ->join('item_master','order_dt.item_code','=','item_master.item_code')
    ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
    ->where('order_hd.tran_no',$trans_no)
    ->where('order_hd.tran_date',date('Y-m-d'))
    ->where('order_dt.tran_date',date('Y-m-d'))
    ->where('order_hd.status_trans','success')
    ->where('order_dt.tran_no',$trans_no)
    ->get();

    
    $pdf = Pdf::loadView('items.bill', compact('dt_data', 'hd_data', 'rest_data'));
    $pdfPath = storage_path('app/public/bills/bill'.$trans_no.'.pdf');
    $pdf->save($pdfPath);

    // Define output image path
    $imagePath = storage_path('app/public/bills/bill'.$trans_no.'.png');

    // Ghostscript command to convert PDF to PNG
    $cmd = "gswin64c -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r144 -sOutputFile=\"{$imagePath}\" \"{$pdfPath}\"";
    exec($cmd, $output, $return_var);

    if ($return_var === 0 && file_exists($imagePath)) {
        return response()->file($imagePath); // ✅ Show or download the image
    } else {
        return response("Conversion failed. Error Code: $return_var", 500);
    }
}

   
    
}
