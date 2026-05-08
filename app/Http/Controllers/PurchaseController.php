<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\Supplier;
use App\Models\PurchaseHead;
use App\Models\PurchaseDetail;
use App\Models\StockLedger;
use DB;

class PurchaseController extends Controller
{

    public function create()
    {
        $materials = RawMaterial::get();

        $suppliers = Supplier::get();

        return view(
            'purchase.create',
            compact('materials','suppliers')
        );
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try{

            $purchase = PurchaseHead::create([

                'purchase_date'=>date('Y-m-d'),

                'supplier_id'=>$request->supplier_id,

                'grand_total'=>$request->grand_total,

            ]);

            foreach($request->material_id as $key => $materialId){

                $qty = $request->qty[$key];

                $rate = $request->rate[$key];

                PurchaseDetail::create([

                    'purchase_id'=>$purchase->id,

                    'material_id'=>$materialId,

                    'qty'=>$qty,

                    'rate'=>$rate,

                    'amount'=>$qty * $rate

                ]);

                $material = RawMaterial::find($materialId);

                $before = $material->current_stock;

                $material->increment(
                    'current_stock',
                    $qty
                );

                $after = $before + $qty;

                StockLedger::create([

                    'material_id'=>$materialId,

                    'type'=>'PURCHASE',

                    'qty'=>$qty,

                    'stock_before'=>$before,

                    'stock_after'=>$after,

                    'reference_no'=>$purchase->id

                ]);
            }

            DB::commit();

            return back()->with(
                'success',
                'Purchase Saved Successfully'
            );

        }catch(\Exception $e){

            DB::rollback();

            return $e->getMessage();
        }
    }
}