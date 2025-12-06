<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseEntry;
use App\Models\PurchaseEntryDt;
use DB;
use Illuminate\Support\Facades\Validator;


class PurchaseEntryController extends Controller
{
    public function create()
    {
        $last = PurchaseEntry::orderBy('trans_no','DESC')->first();

        $items = DB::table('raw_material_master')->get();
        $supplier_masters = DB::table('supplier_master')->get();
        $unit_masters = DB::table('unit_master')->get();
    
        $nextTransNo = $last ? $last->trans_no + 1 : '1';
        $today = date('Y-m-d');
    
        return view('purchase.create', compact('nextTransNo', 'today','items','supplier_masters','unit_masters'));
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rest_cd' => 'required',
            'trans_no' => 'required',
            'trans_date' => 'required|date',
            'supp_cd' => 'required',
            'supp_billno' => 'required',
            'supp_billdt' => 'required|date',
            'porder_no' => 'required',
            'r_off' => 'required|numeric',
            'porder_date' => 'required|date',
            'delivery_challan' => 'required',
            'delivery_date' => 'required|date',
            'item_code.*' => 'required',
            'qty.*' => 'required|numeric|min:0.001',
            'unit_cd.*' => 'required',
            'rate.*' => 'required|numeric|min:0',
            'sgst_per.*' => 'required|numeric|min:0',
        ]);
    
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // echo"<pre>";print_r($request->all());die;
    
        // Save master
        $purchase = PurchaseEntry::create([
            'rest_cd' => $request->rest_cd,
            'trans_no' => $request->trans_no,
            'trans_date' => $request->trans_date,
            'supp_cd' => $request->supp_cd,
            'supp_billno' => $request->supp_billno,
            'supp_billdt' => $request->supp_billdt,
            'porder_no' => $request->porder_no,
            'porder_date' => $request->porder_date,
            'delivery_challan' => $request->delivery_challan,
            'delivery_date' => $request->delivery_date,
            'gross_val' => $request->gross_val,
            'r_off' => $request->r_off,
            'invoice_val' => $request->invoice_val,
        ]);
    
        // Save details
        foreach($request->item_code as $i => $item){
            PurchaseEntryDt::create([
                'rest_cd' => $request->rest_cd,
                'trans_no' => $request->trans_no,
                'trans_date' => $request->trans_date,
                'item_code' => $item,
                'qty' => $request->qty[$i],
                'rate' => $request->rate[$i],
                'unit_cd' => $request->unit_cd[$i],
                'sgst_per' => $request->sgst_per[$i]/2,
                'cgst_per' =>$request->sgst_per[$i]/2,
                'sgst_amt' => $request->sgst_amt[$i],
                'cgst_amt' => $request->cgst_amt[$i],
                'item_total' => $request->total[$i],
            ]);

            $itemQty = 0;

            $itemData = DB::table('raw_material_master')->where('rest_cd',$request->rest_cd)->where('item_code',$item)->first();

            $itemQty = $itemData->qty + $request->qty[$i];

            DB::table('raw_material_master')->where('rest_cd',$request->rest_cd)->where('item_code',$item)->update(['qty'=>$itemQty]);

        }
    
        return response()->json([
            'success' => true,
            'message' => "Saved"
        ]);
    }


        public function createRequest()
    {
        $items = DB::table('raw_material_master')->get();

        $trans_no = DB::table('kitchen_material_issue')->max('trans_no') + 1;

        $unit_masters = DB::table('unit_master')->get();

        return view('kitchen.request-create', compact('items','trans_no','unit_masters'));
    }


    public function save(Request $request)
        {
            // SERVER VALIDATION
            $request->validate([
                'rest_cd' => 'required',
                'trans_date' => 'required|date',
                'requstion_no' => 'required',
                'requstion_date' => 'required|date',
                'item_code.*' => 'required',
                'qty.*' => 'required|numeric|min:0.01',
                'unit_cd.*' => 'required'
            ],[
                'item_code.*.required' => 'Item is required',
                'qty.*.required' => 'Qty is required',
                'unit_cd.*.required' => 'Unit is required'
            ]);

            try{

                // INSERT HEADER
                $trans_no = DB::table('kitchen_material_issue')->insertGetId([
                    'rest_cd' => $request->rest_cd,
                    'trans_date' => $request->trans_date,
                    'requstion_no' => $request->requstion_no,
                    'requstion_date' => $request->requstion_date,
                    'status' =>'P',
                ]);

                // INSERT DETAILS
                foreach($request->item_code as $k => $item){
                    DB::table('kitchen_material_issue_dt')->insert([
                        'rest_cd' => $request->rest_cd,
                        'trans_no' => $trans_no,
                        'trans_date' => $request->trans_date,
                        'item_code' => $item,
                        'qty' => $request->qty[$k],
                        'issue_qty' => 0,
                        'unit_cd' => $request->unit_cd[$k],
                        'remark' => $request->remark[$k],
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Request Successfully Saved!'
                ]);

            } catch (\Exception $e) {

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }


        public function pendingRequest()
        {
            $pending = DB::table('kitchen_material_issue')->where('status', 'P')->get();

            
            return view('store.pending-request', compact('pending'));
        }


        public function requestView($id)
        {
            $hd = DB::table('kitchen_material_issue')->where('trans_no',$id)->first();

            $dt =  DB::table('kitchen_material_issue_dt')->select('kitchen_material_issue_dt.*','raw_material_master.item_desc','unit_master.unit_small_desc')->where('trans_no',$id)
                   ->join('raw_material_master','kitchen_material_issue_dt.item_code','=','raw_material_master.item_code')
                   ->join('unit_master','kitchen_material_issue_dt.unit_cd','=','unit_master.unit_cd')
                   ->get();

            return view('store.request-view', compact('hd', 'dt'));
        }


        public function issueSave(Request $request)
        {
            $hd_id = $request->hd_id;

            foreach($request->dt_id as $key => $dtid){
                // update detail record
                DB::table('kitchen_material_issue_dt')->where('trans_no',$hd_id)->where('item_code',$request->item_code[$key])->update([
                    'issue_qty' => $request->issue_qty[$key],
                ]);
            }

            DB::table('kitchen_material_issue')->where('trans_no',$hd_id)->update([
                'status' =>'A',
            ]);

       

            return response()->json([
                'success' => true,
                'message' => 'Material issued successfully!'
            ]);
        }



    
}
