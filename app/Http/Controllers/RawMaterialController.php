<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryMaster;
use App\Models\UnitMaster;
use App\Models\RawMaterialMaster;
use App\Models\SupplierMaster;

class RawMaterialController extends Controller
{
    public function raw_mat_mast_form()
    {
        $catMast = CategoryMaster::orderBy('catg_name')->get();
        $suppMast = SupplierMaster::orderBy('supp_name')->get();
        $unitMast = UnitMaster::get();
        return view('raw_material.raw_mat_mast_form',compact('catMast','unitMast','suppMast'));
    }

    public function raw_mat_mast_store(Request $request)
    {
        $request->validate([
            'item_desc' => 'required|string|max:255',
            'qty' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,3})?$/',
            'unit_cd' => 'required|string|max:10',
            'catg_cd' => 'required',
        ], [
            'item_desc.required' => 'Item description is required',
            'qty.required' => 'Quantity is required',
            'qty.numeric' => 'Quantity must be a valid number',
            'qty.regex' => 'Qty allows max 3 decimal digits (ex: 10 or 10.326)',
            'unit_cd.required' => 'Unit code is required',
            'catg_cd.required' => 'Category code is required'
        ]);

        RawMaterialMaster::create([
            'rest_cd'     => '01',
            'item_desc'   => $request->item_desc,
            'qty'         => $request->qty,
            'unit_cd'     => $request->unit_cd,
            'remark'      => $request->remark,
            'supp_cd'   => $request->supp_code,
            'supp_billno' => $request->supp_billno,
            'supp_billdt' => $request->supp_billdt,
            'catg_cd'     => $request->catg_cd,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Record saved successfully!'
        ]);
    }

    public function supplier_mast_form()
    {
        return view('supplier_master.suplr_mast_form');
    }

    public function supplier_mast_store(Request $request)
    {
        $request->validate([
            'supp_name' => 'required|string|max:255',
            'supp_add1' => 'required',
            'city' => 'required|string',
            'gst_no' => 'required',
            'contact_no' => 'nullable|max:15',
        ], [
            'supp_name.required' => 'Supplier Nameis required',
            'supp_add1.required' => 'Address 1 is required',
            'city.required' => 'City is required',
            'gst_no.required' => 'GST No is required',
            'contact_no.max' => 'Contact no max 15 digit',
        ]);

        SupplierMaster::create([
            'rest_cd'     => '01',
            'supp_name'   => $request->supp_name,
            'supp_add1'   => $request->supp_add1,
            'supp_add2'   => $request->supp_add2,
            'city'        => $request->city,
            'gst_no'      => $request->gst_no,
            'contact_person' => $request->contact_person,
            'contact_no'  => $request->contact_no,
            'remark'      => $request->remark,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Record saved successfully!'
        ]);
    }

}
