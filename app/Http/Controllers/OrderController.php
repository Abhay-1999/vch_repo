<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


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
            ->get();
        }else{
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('flag','!=','D')
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
        public function refresh()
        {
       
            $orders = DB::table('order_hd')
            ->where('tran_date',date('Y-m-d'))
            ->where('flag','!=','D')
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

        public function indexp()
    {
        $orders = DB::table('order_hd')
        // ->where('order_hd.status_trans','pending')
        ->where('tran_date',date('Y-m-d'))
        ->where('flag','!=','D')
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
        ->where('flag','!=','D')
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
        
        return view('orders.pos');
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

        if($itemStatus=='A'){
            $status = 'D';
        }else{
            $status = 'A';
        }
        // Example update logic (you should replace this with actual DB logic)
        DB::table('item_master')
            ->where('item_code', $itemCode)
            ->where('rest_code', $rest_code)
            ->update(['item_status' => $status]); // or any custom logic

        return response()->json(['message' => 'Status updated']);
    }


    public function printContent(Request $request)
    {
        $trans_no = $request->input('trans_no');
        $type = $request->input('type'); // 'token', 'bill', or 'both'
        $date = $request->input('date'); // 'token', 'bill', or 'both'
    
        $group_code = Session::get('group_code');
        $rest_code = Session::get('rest_code');


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
            ->select('order_dt.*', 'item_master.item_desc', 'item_master.item_gst as igst','item_master.item_rate')
            ->join('item_master', 'order_dt.item_code', '=', 'item_master.item_code')
            ->join('order_hd', 'order_dt.tran_no', '=', 'order_hd.tran_no')
            ->where('order_hd.tran_no', $trans_no)
            ->where('order_hd.tran_date',$date)
            ->where('order_dt.tran_date',$date)
            ->where('order_hd.status_trans', 'success')
            ->where('order_dt.tran_no', $trans_no)
            ->get();
    
        session()->forget('cart');
    
        // Return HTML(s) based on type
        if ($type === 'bill') {
            $html = view('items.bill2', compact('dt_data', 'hd_data', 'rest_data'))->render();
            return response()->json(['html' => $html]);
        } elseif ($type === 'token') {
            $html = view('items.bill', compact('dt_data', 'hd_data', 'rest_data'))->render(); // You must create this view
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
    

}
