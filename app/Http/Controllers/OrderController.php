<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function dashboard(){

        $itemWiseSales = DB::table('order_dt')
        ->join('item_master', 'order_dt.item_code', '=', 'item_master.item_code')
        ->select('item_master.item_desc', DB::raw('SUM(order_dt.item_qty * item_master.item_rate) as total'))
        ->groupBy('item_master.item_desc')
        ->orderByDesc('total')
        ->get();

        $paymodeSales = DB::table('order_hd')
        ->select('payment_mode', DB::raw('SUM(paid_amt) as total'))
        ->where('tran_date', date('Y-m-d'))
        ->groupBy('payment_mode')
        ->get();
        
        // Map short codes to readable labels
        $labelMap = [
            'O' => 'Online',
            'C' => 'Cash',
            'Z' => 'Zomato',
            'S' => 'Swiggy',
            'U' => 'Counter UPI'
        ];
        
        $paymodeLabels = [];
        $paymodeTotals = [];
        
        foreach ($paymodeSales as $row) {
            $label = $labelMap[$row->payment_mode] ?? $row->payment_mode;
            $paymodeLabels[] = $label;
            $paymodeTotals[] = $row->total;
        }

         // ✅ 1. Today’s total sales
    $todaySales = DB::table('order_hd')
    ->where('tran_date', date('Y-m-d'))
    ->sum('paid_amt');

// ✅ 2. Monthly overall sales (last 6 months)
$monthlySales = DB::table('order_hd')
    ->select(
        DB::raw("DATE_FORMAT(tran_date, '%b') as month"),  // Jan, Feb, etc.
        DB::raw("SUM(paid_amt) as total")
    )
    ->where('tran_date', '>=', now()->subMonths(5)->startOfMonth())
    ->groupBy(DB::raw("DATE_FORMAT(tran_date, '%b')"))
    ->orderBy(DB::raw("MIN(tran_date)"))
    ->get();

$monthLabels = $monthlySales->pluck('month');
$monthTotals = $monthlySales->pluck('total');
    
        return view('dashboard', ['itemWiseSales' => $itemWiseSales,'paymodeLabels'=>$paymodeLabels,'paymodeTotals'=>$paymodeTotals,'todaySales' => $todaySales,
        'monthLabels' => $monthLabels,
        'monthTotals' => $monthTotals,]);
    }

    public function index()
    {
      

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
        if($role=='2'){
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('status_trans','success')
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->orderBy('tran_no','desc')
            ->get();
        }else{
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->where('status_trans','success')
            ->orderBy('tran_no','desc')
            ->get();
        }

        
     
        $order_arr = array();
        $order_arr_item = array();
        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }

       
        // echo"<pre>";print_r($order_arr_item_f);die;

        return view('orders.index', compact('orders','order_arr','role','order_arr_item','order_arr_item_f')); // Pass orders to the view
    }

    public function indexCash()
    {
      

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
        if($role=='2'){
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('status_trans','success')
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->whereIn('payment_mode',['C','U'])
            ->orderBy('tran_no','desc')
            ->get();
        }else{
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->where('status_trans','success')
            ->whereIn('payment_mode',['C','U'])
            ->orderBy('tran_no','desc')
            ->get();
        }

        
     
        $order_arr = array();
        $order_arr_item = array();
        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }

       
        // echo"<pre>";print_r($order_arr_item_f);die;

        return view('orders.index_cash', compact('orders','order_arr','role','order_arr_item','order_arr_item_f')); // Pass orders to the view
    }

    public function indexZomato()
    {
      

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
        if($role=='2'){
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('status_trans','success')
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->whereIn('payment_mode',['Z','S'])
            ->orderBy('tran_no','desc')
            ->get();
        }else{
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->where('status_trans','success')
            ->whereIn('payment_mode',['Z','S'])
            ->orderBy('tran_no','desc')
            ->get();
        }

        
     
        $order_arr = array();
        $order_arr_item = array();
        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }

       
        // echo"<pre>";print_r($order_arr_item_f);die;

        return view('orders.index_zomato', compact('orders','order_arr','role','order_arr_item','order_arr_item_f')); // Pass orders to the view
    }

    public function indexOnline()
    {
      

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
        if($role=='2'){
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('status_trans','success')
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->where('payment_mode','O')
            ->orderBy('tran_no','desc')
            ->get();
        }else{
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('flag','!=','D')
            ->where('flag','!=','H')
            ->where('status_trans','success')
            ->where('payment_mode','O')
            ->orderBy('tran_no','desc')
            ->get();
        }

        
     
        $order_arr = array();
        $order_arr_item = array();
        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }

       
        // echo"<pre>";print_r($order_arr_item_f);die;

        return view('orders.index_online', compact('orders','order_arr','role','order_arr_item','order_arr_item_f')); // Pass orders to the view
    }

    public function OrderDetail($trans_no)
    {
        $orders = DB::table('order_dt')->select('order_dt.*','item_master.item_desc')
        ->join('item_master','order_dt.item_code','=','item_master.item_code')
        ->where('order_dt.tran_no',$trans_no)
        ->get();

        return view('orders.order_dtl', compact('orders')); // Pass orders to the view
    }

    // Mark as delivered
        public function deliver(Request $request)
        {
            $order = DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->first();

            if ($order) {
                DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->update(['flag'=>'D']);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false]);
        }

        // Refresh latest orders and return blade partial
        public function refresh(Request $request)
        {
            if($request->orderType=='A'){

                $orders = DB::table('order_hd')
                ->where('tran_date',date('Y-m-d'))
                ->where('flag','!=','D')
                ->where('flag','!=','H')
                ->where('status_trans','success')
                ->orderBy('tran_no','desc')
                ->get();

            }else if($request->orderType=='C'){
                $orders = DB::table('order_hd')
                ->where('tran_date',date('Y-m-d'))
                ->where('flag','!=','D')
                ->where('flag','!=','H')
                ->where('status_trans','success')
                ->whereIn('payment_mode',['C','U'])
                ->orderBy('tran_no','desc')
                ->get();
            }else if($request->orderType=='Z'){
                $orders = DB::table('order_hd')
                ->where('tran_date',date('Y-m-d'))
                ->where('flag','!=','D')
                ->where('flag','!=','H')
                ->where('status_trans','success')
                ->whereIn('payment_mode',['Z','S'])
                ->orderBy('tran_no','desc')
                ->get();
            }else if($request->orderType=='O'){
                $orders = DB::table('order_hd')
                ->where('tran_date',date('Y-m-d'))
                ->where('flag','!=','D')
                ->where('flag','!=','H')
                ->where('status_trans','success')
                ->whereIn('payment_mode',['O'])
                ->orderBy('tran_no','desc')
                ->get();
            }
            
            $order_arr = array();

            $admin = Auth::guard('admin')->user();
            $role = $admin->role;

            $order_arr_item = array();

            $order_arr_item_f = array();

            foreach($orders as $order){
    
                    $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                    ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                    ->join('item_master','order_dt.item_code','=','item_master.item_code')
                    ->where('order_dt.tran_no',$order->tran_no)
                    ->where('order_hd.tran_date',date('Y-m-d'))
                    ->where('order_dt.tran_date',date('Y-m-d'))
                    ->get();
    
                    foreach($details as $detail){
                        $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                        $order_arr_item[$order->tran_no][] = $detail->item_code;
                        $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                    }
    
            }
    
           
            // echo"<pre>";print_r($order_arr_item);die;
    
            return view('orders.orders_table', compact('orders','order_arr','role','order_arr_item','order_arr_item_f'));
        }

        public function updateFlag(Request $request)
        {
            $order = DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->first();

            if ($order) {
                DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->update(['flag'=> $request->flag]);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false]);
        }

        public function hold(Request $request)
        {
            $order = DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->first();

            if ($order) {
                DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->update(['flag'=>'H','net_amt'=>'0.00','gross_amt'=>'0.00','paid_amt'=>'0.00']);

                DB::table('order_dt')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->update(['customise_flag'=>'H','amount'=>'0.00','item_gst'=>'0.00']);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false]);
        }

        public function indexp()
    {
        $orders = DB::table('order_hd')
        // ->where('order_hd.status_trans','pending')
        ->where('tran_date',date('Y-m-d'))
        ->where('status_trans','success')
        ->where('flag','!=','D')
        ->where('flag','!=','H')
        ->orderBy('tran_no','desc')
        ->get();

        $admin = Auth::guard('admin')->user();

        
        $role = $admin->role;
        // echo $role;die;
        $order_arr = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_dt.tran_no',$order->tran_no)
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc . ' - ' . $detail->item_qty;
                }

        }

        // echo implode(', ', $order_arr[1]);die;

        return view('orders.indexp', compact('orders','order_arr','role')); // Pass orders to the view
    }

    public function refreshp()
    {
   
        $orders = DB::table('order_hd')
        ->where('tran_date',date('Y-m-d'))
        ->where('status_trans','success')
        ->where('flag','!=','D')
        ->where('flag','!=','H')
        ->orderBy('tran_no','desc')
        ->get();
        
        $order_arr = array();

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;

        foreach($orders as $order){

                $details =  DB::table('order_dt')->select('order_dt.item_qty','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_dt.tran_no',$order->tran_no)
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))

                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc . ' - ' . $detail->item_qty;
                }

        }
        return view('orders.ordersp_table', compact('orders','order_arr','role'));
    }


    public function makePayment(Request $request)
    {
        $order = DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->first();

        if ($order) {
            DB::table('order_hd')->where('tran_date',date('Y-m-d'))->where('tran_no', $request->tran_no)->update(['status_trans'=>'success']);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    function create_order(){

        $customers = DB::table('customer_masters')->get();
        
        return view('orders.pos',compact('customers'));
    }

    
public function updateOrderItem(Request $request)
{
    $tran_no = $request->tran_no;
    $item_code = $request->item_code;

    // Assuming order_dt has item index or serial number
    DB::table('order_dt')
        ->where('tran_no', $tran_no)
        ->where('item_code', $item_code)
        ->update(['customise_flag' => 'D']); // or some other flag

    return response()->json(['success' => true]);
}

    public function markOrderComplete(Request $request)
    {
        $tran_no = $request->tran_no;
        DB::table('order_hd')
        ->where('tran_date',date('Y-m-d'))
            ->where('tran_no', $tran_no)
            ->update(['flag' => 'D']); // or some other flag

        return response()->json(['success' => true]);
    }

    function delivered(){

        $orders = DB::table('order_hd')
        ->where('status_trans','success')
        ->where('tran_date',date('Y-m-d'))
        ->orderBy('tran_no','desc')
        ->where('flag','D')
        ->get();

        $order_arr = array();

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;

        $order_arr_item = array();

        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }


        return view('orders.delivered', compact('orders','order_arr','role','order_arr_item','order_arr_item_f'));
        
    }

    public function items(){

        $items = DB::table('item_master')
        ->get();

        return view('orders.items', compact('items'));
    }

        public function updateStatus(Request $request)
    {
        $itemCode = $request->input('item_code');
        $itemStatus = $request->input('item_status');
        $rest_code = $request->input('rest_code');
        $minutes = $request->input('minutes');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if($itemStatus=='A'){
            $status = 'D';
        }else{
            $status = 'A';
        }

        if($minutes!='none'){
            if ($itemStatus === 'A') {
                $minutes = (int) $minutes;
    
                // Get time after X minutes in IST (Asia/Kolkata)
                $futureTime = now('Asia/Kolkata')->addMinutes($minutes)->format('H:i:s'); // 👈 Only time string
                
                // echo $futureTime;die;
                DB::table('item_master')
                    ->where('item_code', $itemCode)
                    ->where('rest_code', $rest_code)
                    ->update([
                        'item_status' => 'D',
                        'minutes' => $futureTime
                    ]);
            
                return response()->json([
                    'message' => 'Item deactivated and will auto-reactivate after ' . $minutes . ' minutes'
                ]);
            }
            
    
            if ($itemStatus === 'D') {
                $minutes = (int) $minutes;
    
                // Get time after X minutes in IST (Asia/Kolkata)
                $futureTime = now('Asia/Kolkata')->addMinutes($minutes)->format('H:i:s'); // 👈 Only time string
                
                
            
                DB::table('item_master')
                    ->where('item_code', $itemCode)
                    ->where('rest_code', $rest_code)
                    ->update([
                        'item_status' => 'A',
                        'minutes' => $futureTime // store future timestamp
                    ]);
            
                return response()->json([
                    'message' => 'Item Activated and will auto-deactivate after ' . $minutes . ' minutes'
                ]);
            }

        }else{

            DB::table('item_master')
            ->where('item_code', $itemCode)
            ->where('rest_code', $rest_code)
            ->update([
                'item_status' =>  $status,
                'start_time' => $start_time ,
                'end_time' => $end_time
            ]);

            
            return response()->json([
                'message' => 'Item schedule has been updated successfully.'
            ]);

        }

      
        

        return response()->json(['message' => 'Status updated']);
    }


    public function printContent(Request $request)
    {
        $trans_no = $request->input('trans_no');
        $type = $request->input('type'); // 'token', 'bill', or 'both'
        $date = $request->input('date'); // 'token', 'bill', or 'both'
    
        $group_code = '01';
        $rest_code = '01';


        if(!$request->input('date')){
            $date = date('Y-m-d');
        }else{
            $date = $request->input('date'); 
        }
    
        // Header data
        $hd_data = DB::table('order_hd')
            ->where('tran_no', $trans_no)
            ->where('tran_date', $date)
            ->where('status_trans', 'success')
            ->first();


        $cust_hd_data = DB::table('order_hd')
        ->join('customer_masters', 'order_hd.customer_id', '=', 'customer_masters.id')
        ->where('tran_no', $trans_no)
        ->where('tran_date', $date)
        ->where('status_trans', 'success')
        ->select('customer_masters.*') // Or whichever fields you need
        ->first();
        
    
        // if (!$hd_data) {
        //     return response()->json(['error' => 'Order not found.'], 404);
        // }
    
        // Restaurant data
        $rest_data = DB::table('chain_master')
            ->where('group_code', $group_code)
            ->where('rest_code', $rest_code)
            ->first();
    
        // Detail data
        $dt_data = DB::table('order_dt')
            ->select('order_dt.*', 'item_master.item_desc', 'item_master.item_hdesc', 'item_master.item_gst as igst','item_master.item_rate','item_master.store')
            ->join('item_master', 'order_dt.item_code', '=', 'item_master.item_code')
            ->join('order_hd', 'order_dt.tran_no', '=', 'order_hd.tran_no')
            ->where('order_hd.tran_no', $trans_no)
            ->where('order_hd.tran_date',$date)
            ->where('order_dt.tran_date',$date)
            ->where('order_hd.status_trans', 'success')
            ->where('order_dt.tran_no', $trans_no)
            ->get();
    
        session()->forget('cart');

        $fullNumber = $hd_data->invoice_no;

        // Convert to string to safely extract substrings
        $fullStr = str_pad($fullNumber, 10, '0', STR_PAD_LEFT); // ensures full 10 digits

        $prefix = substr($fullStr, 0, 2);     // '25'
        $branch = substr($fullStr, 2, 2);     // '26'
        $serial = (int)substr($fullStr, 4);   // converts '0000005' to integer 5

        $invoiceNo = $prefix.'-'.$branch.'/'.$serial;

        if($hd_data->payment_mode=='O'){
            $paymentMode = 'Online';
        }elseif($hd_data->payment_mode=='C'){
            $paymentMode = 'Cash';
        }elseif($hd_data->payment_mode=='U'){
            $paymentMode = 'Counter UPI';
        }elseif($hd_data->payment_mode=='Z'){
            $paymentMode = 'Zomato';
        }elseif($hd_data->payment_mode=='S'){
            $paymentMode = 'Swiggy';
        }

    
        // Return HTML(s) based on type
        if ($type === 'bill') {
            $html = view('items.bill2', compact('dt_data', 'hd_data', 'rest_data','invoiceNo','paymentMode','cust_hd_data'))->render();
            return response()->json(['html' => $html]);
        } elseif ($type === 'token') {
            $html = view('items.bill', compact('dt_data', 'hd_data', 'rest_data','paymentMode'))->render(); // You must create this view
            return response()->json(['html' => $html]);
        } elseif ($type === 'both') {
            $tokenHtml = view('items.both', compact('dt_data', 'hd_data', 'rest_data'))->render();
            return response()->json(['html' =>$tokenHtml]);
        }
    
        return response()->json(['error' => 'Invalid print type.'], 400);
    }


    public function refreshdelivered(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        if(!$date){
            $date = date('Y-m-d');
        }else{
            $date = $date;
        }

        $orders = DB::table('order_hd')
        ->where('tran_date',$date)
        ->where('flag','D')
        ->orderBy('tran_no','desc')
        ->get();
        
        $order_arr = array();

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;

        $order_arr_item = array();

        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',$date)
                ->where('order_dt.tran_date',$date)
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }

       
        // echo"<pre>";print_r($order_arr_item);die;

        return view('orders.orders_table_delivered', compact('orders','order_arr','role','order_arr_item','order_arr_item_f'));
    }
    

     
   public function initiatePayment(Request $request)
    {
        $order_id = "ORD" . time(); // You can save this in DB if needed
        $carts = session()->get('cart');

          $returnUrl = route('payment.status');

          $group_code = '01';
          $rest_code = '01';
          $paymode_mode = 'O';
          $confirm_order = 'Y';
  
          $taxes = $itemWiseAmt = 0; 
  
          foreach($carts as $cart){
  
              $item_gst = DB::table('item_master')->where('group_code',$group_code)->where('rest_code',$rest_code)
              ->where('item_code',$cart['item_code'])->value('item_gst');
  
               $itemWiseAmt += $cart['quantity']*$cart['price'];
               $taxes += ($item_gst*$itemWiseAmt/100);
  
          }


          $amount = $itemWiseAmt;

        //   echo $itemWiseAmt;die;


        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode('EA317F35F824F60A1200589607AD8E'. ':'),
            'Content-Type' => 'application/json',
            'x-merchantid' => 'SG3164',
            'x-customerid' => '355',
        ])->post('https://smartgatewayuat.hdfcbank.com/session', [
            "order_id" => $order_id,
            "amount" => $amount,
            "customer_id" => '355',
            "customer_email" => "test@example.com",
            "customer_phone" => "9999999999",
            "payment_page_client_id" => 'hdfcmaster',
            "action" => "paymentPage",
            "currency" => "INR",
            "callback_url" => $returnUrl,
            "description" => "Test Payment",
            "first_name" => "John",
            "last_name" => "Doe"
        ]);

        $data = $response->json();
        
        // echo"<pre>";print_r($data);die;
        
        session(['last_order_id' =>$data['id']]); // $order_id should be your actual order ID
    // $orderId = session('last_order_id');
       
  
      

        $convin_amt = 0;

        $convin_amt_gst = 0;

        $final_conv =  $convin_amt + $convin_amt_gst;

        $paid_amt = $amount;

        $service_charge = 0;
        
        $sgst_service = 0;

        $cgst = 0;

        $gross_amt = $cgst + $cgst + $amount;
        $transactionNumber = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

        $status = 'pending';
        
        $mobile =  Session::get('phone');

        $trans_no =  DB::table('temp_order_hd')->where(['rest_code' => $rest_code,'tran_date'=>date('Y-m-d')])->orderby('tran_no','desc')->value('tran_no');

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
            'order_id' =>$order_id, 
            'service_charge'=>$convin_amt, 
            'service_cgst' =>$convin_amt_gst/2, 
            'service_sgst' =>$convin_amt_gst/2, 
            'email' =>'test@gmail.com', 
            'status_trans' =>$status, 
            'flag' => 'S', 
            'confirm_order' => $confirm_order, 
            'transaction_no' =>$transactionNumber, 
            'payment_mode' =>'O', 
        ];

        DB::table('temp_order_hd')->insertGetId($order_hd);

    //    echo $trans_no;die;

       DB::table('temp_order_hd')
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


            DB::table('temp_order_dt')->insert([
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

        session()->forget('cart');

       
        if (
            isset($data['payment_links']['web']) &&
            !empty($data['payment_links']['web'])
        ) {
            // Optionally save $order_id and $data['response']['id'] in DB
            return redirect()->away($data['payment_links']['web']);
        }

        return response()->json([
            'error' => true,
            'message' => 'Unable to generate payment page',
            'response' => $data
        ]);
    }

    /**
     * Step 2: Handle Return URL After Payment
     */
   public function paymentStatus(Request $request)
{
   // echo"<pre>";print_r($request->all());die;
    $orderId = $request['order_id']; // or get from DB
    // OR try reading from GET if HDFC passes it
    // $orderId = $request->query('order_id');
    
    //  echo $orderId;die;

    if (!$orderId) {
        return "No order ID found to check status.";
    }

    // Call HDFC status check API here
    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . base64_encode('EA317F35F824F60A1200589607AD8E'. ':'),
        'Content-Type' => 'application/json',
        'x-merchantid' => 'SG3164',
        'x-customerid' => '355',
    ])->post('https://smartgatewayuat.hdfcbank.com/orders/', [
        'order_id' => $orderId,
    ]);

    $result = $response->json();
    
    // echo"<pre>";print_r($result);die;

    $order_id = $result['order_id'];
    $amount = $result['amount'];
    $status = $result['status'];

    $data = DB::table('temp_order_hd')->where('order_id',$order_id)->where('tran_date',date('Y-m-d'))->where('status_trans','pending')->first();

    if(!empty($data) && $data->paid_amt == $amount && $status == 'CHARGED'){

        DB::table('temp_order_hd')->where('order_id',$order_id)->where('tran_date',date('Y-m-d'))->where('status_trans','pending')->update(['status_trans'=>'success','reponce_data'=>$result['payment_gateway_response']]);


        $temp_data = DB::table('temp_order_hd')->where('order_id',$order_id)->where('tran_date',date('Y-m-d'))->where('status_trans','success')->first();

        $lastInvoice = DB::table('order_hd')->orderBy('invoice_no', 'desc')->first();

        // if ($lastInvoice) {
        //     $newInvoiceNumber = $lastInvoice->invoice_no + 1;
        // } else {
        //     // First invoice number
        //     $newInvoiceNumber = 20252600000000;
        // }

    
        $tran_no =  DB::table('order_hd')->where(['rest_code'=>$temp_data->rest_code,'tran_date'=>date('Y-m-d')])->orderby('tran_no','desc')->value('tran_no');

        if($tran_no){
            $tran_no = $tran_no + 1;
        }else{
            $tran_no = 1;
        }
        
        $order_hd = [
            'group_code' => $temp_data->group_code,
            'rest_code' => $temp_data->rest_code,
            'tran_no' => $tran_no,
            'net_amt' => $temp_data->net_amt, 
            'cgst_amt' => $temp_data->cgst_amt, 
            'sgst_amt' => $temp_data->sgst_amt, 
            'gross_amt' =>$temp_data->gross_amt, 
            'paid_amt' => round($temp_data->paid_amt), 
            'order_id' =>$temp_data->order_id, 
            'service_charge'=>$temp_data->service_charge, 
            'service_cgst' =>$temp_data->service_cgst, 
            'service_sgst' =>$temp_data->service_sgst,
            'email' =>$temp_data->email, 
            'status_trans' =>$temp_data->status_trans, 
            'flag' => 'S', 
            'confirm_order' => $temp_data->confirm_order, 
            'transaction_no' =>$temp_data->transaction_no, 
            'reponce_data' =>$temp_data->reponce_data, 
            'payment_mode' =>'O', 
        ];

        DB::table('order_hd')->insertGetId($order_hd);


        $carts = DB::table('temp_order_dt')->where('group_code',$temp_data->group_code)->where('rest_code',$temp_data->rest_code)->where('tran_no', $temp_data->tran_no)->where('tran_date',date('Y-m-d'))->get();

        foreach($carts as $cart){
            // echo"<pre>";print_r($cart);die;


            DB::table('order_dt')->insert([
                'group_code' => $cart->group_code,
                'rest_code' => $cart->rest_code,
                'tran_no' => $tran_no,
                'item_code' =>$cart->item_code,
                'item_qty' => $cart->item_qty,
                'customise_flag' => 'S',
                'amount' =>$cart->amount,
                'item_gst' =>$cart->item_gst,
            ]);
        }







        $hd_data =   DB::table('order_hd')->where('order_id',$order_id)->where('tran_date',date('Y-m-d'))->where('status_trans','success')->first();

        $trans_no = $hd_data->tran_no;

        $rest_data =  DB::table('chain_master')->where('group_code','01')->where('rest_code','01')->first();

        $dt_data =   DB::table('order_dt')->select('order_dt.*','item_master.item_desc','item_master.item_gst as igst','item_master.item_rate')
        ->join('item_master','order_dt.item_code','=','item_master.item_code')
        ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
        ->where('order_hd.tran_no',$trans_no)
        ->where('order_dt.tran_date',date('Y-m-d'))
        ->where('order_hd.tran_date',date('Y-m-d'))
        ->where('order_hd.status_trans','success')
        ->where('order_dt.tran_no',$trans_no)
        ->get();

      // $this->generateBillImage($trans_no,$mobile);



        
        
         return view('items.bill', compact('dt_data', 'hd_data', 'rest_data'));
    }else{
        DB::table('order_hd')->where('order_id',$order_id)->where('tran_date',date('Y-m-d'))->where('status_trans','pending')->update(['status_trans'=>'failure']);
        
        return redirect()->route('items.index')->with('error', 'Payment failed. Please try again.');

    }



}

    public function reverseGST($totalAmount, $gstRate) {
        $baseAmount = ($totalAmount * 100) / (100 + $gstRate);
        $gstAmount = $totalAmount - $baseAmount;
        return [
            'base' => round($baseAmount, 2),
            'gst'  => round($gstAmount, 2)
        ];
    }

    public function ChangeOrder(){

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
  
        $orders = DB::table('order_hd')
        ->where('tran_date',date('Y-m-d'))
        ->where('flag','!=','H')
        ->whereIn('payment_mode',['C','U'])
        ->where('status_trans','success')
        ->orderBy('tran_no','desc')
        ->get();
        

        
     
        $order_arr = array();
        $order_arr_item = array();
        $order_arr_item_f = array();

        foreach($orders as $order){

                $details = DB::table('order_dt')->select('order_dt.item_qty','order_dt.customise_flag','order_dt.item_code','item_master.item_desc','order_hd.flag')
                ->join('order_hd','order_dt.tran_no','=','order_hd.tran_no')
                ->join('item_master','order_dt.item_code','=','item_master.item_code')
                ->where('order_hd.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_date',date('Y-m-d'))
                ->where('order_dt.tran_no',$order->tran_no)
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc  . ' - ' . $detail->item_qty;
                    $order_arr_item[$order->tran_no][] = $detail->item_code;
                    $order_arr_item_f[$order->tran_no][$detail->item_code] = $detail->customise_flag;
                }

        }

       
        // echo"<pre>";print_r($order_arr_item_f);die;

          return view('orders.changeorder', compact('orders','order_arr','role','order_arr_item','order_arr_item_f')); // Pass orders
    }


    public function UpdatePaymode(Request $request){

        $tran_no = $request->tran_no;
        $flag = $request->flag;

        if($flag=='C'){
            $status = 'U';
        }else{
            $status = 'C';
        }

        DB::table('order_hd')
        ->where('tran_date',date('Y-m-d'))
            ->where('tran_no', $tran_no)
            ->update(['payment_mode' => $status]); // or some other flag

        return response()->json(['success' => true]);
    }


    public function getDiscount(Request $request)
    {
        $orderType = $request->input('order_type');
      

        if ($orderType=='Z') {
            $discount = DB::table('chain_master')->first();
            return response()->json([
                'success' => true,
                'discount_percent' => $discount->zomato
            ]);
        }elseif($orderType=='S'){
            $discount = DB::table('chain_master')->first();
            return response()->json([
                'success' => true,
                'discount_percent' => $discount->swiggy
            ]);
        } else {
            return response()->json([
                'success' => false,
                'discount_percent' => 0
            ]);
        }
    }


}
