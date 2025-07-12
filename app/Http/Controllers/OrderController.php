<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function index()
    {
      

        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
        if($role=='2'){
            $orders = DB::table('order_hd')
            ->where('status_trans','success')
            ->where('flag','!=','D')
            ->get();
        }else{
            $orders = DB::table('order_hd')
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
            $order = DB::table('order_hd')->where('tran_no', $request->tran_no)->first();

            if ($order) {
                DB::table('order_hd')->where('tran_no', $request->tran_no)->update(['flag'=>'D']);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false]);
        }

        // Refresh latest orders and return blade partial
        public function refresh()
        {
       
            $orders = DB::table('order_hd')
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
            $order = DB::table('order_hd')->where('tran_no', $request->tran_no)->first();

            if ($order) {
                DB::table('order_hd')->where('tran_no', $request->tran_no)->update(['flag'=> $request->flag]);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false]);
        }

        public function indexp()
    {
        $orders = DB::table('order_hd')
        // ->where('order_hd.status_trans','pending')
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
                ->get();

                foreach($details as $detail){
                    $order_arr[$order->tran_no][] = $detail->item_desc . ' - ' . $detail->item_qty;
                }

        }
        return view('orders.ordersp_table', compact('orders','order_arr','role'));
    }


    public function makePayment(Request $request)
    {
        $order = DB::table('order_hd')->where('tran_no', $request->tran_no)->first();

        if ($order) {
            DB::table('order_hd')->where('tran_no', $request->tran_no)->update(['status_trans'=>'success']);

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
        ->where('tran_no', $tran_no)
        ->update(['flag' => 'D']); // or some other flag

    return response()->json(['success' => true]);
}

}
