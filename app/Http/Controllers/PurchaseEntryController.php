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
    
}
