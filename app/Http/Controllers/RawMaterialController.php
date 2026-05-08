<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryMaster;
use App\Models\UnitMaster;
use App\Models\RawMaterialMaster;
use App\Models\SupplierMaster;

class RawMaterialController extends Controller
{

    public function raw_mat_mast_index()
    {
        $materials = RawMaterialMaster::orderBy('id', 'desc')->paginate(10);

        return view('raw_material.index', compact('materials'));
    }

    public function raw_mat_mast_form()
    {
        $catMast = CategoryMaster::orderBy('catg_name')->get();
        $suppMast = SupplierMaster::orderBy('supp_name')->get();
        $unitMast = UnitMaster::get();

          $last = RawMaterialMaster::orderBy('id', 'desc')->first();
         $nextId = $last ? $last->id + 1 : 1;
         $auto_code = 'RM-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        return view('raw_material.raw_mat_mast_form',compact('catMast','unitMast','suppMast','auto_code'));
    }

    public function raw_mat_mast_store(Request $request)
    {
       $request->validate([
            'material_code'  => 'required|unique:raw_material_master,material_code|max:50',
            'material_name'  => 'required|max:150',

            'gst_rate'       => 'nullable|numeric|min:0|max:100',
            'mrp'            => 'nullable|numeric|min:0',
       

            'min_stock_level'  => 'nullable|numeric|min:0',
            'max_stock_level'  => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',

            'perishable'     => 'nullable|in:0,1',
            'batch_tracked'  => 'nullable|in:0,1',
            'active'         => 'nullable|in:0,1',
        ]);

        $data = new RawMaterialMaster();

        $data->material_code = $request->material_code;
        $data->material_name = $request->material_name;
        $data->description = $request->description;
        $data->category = $request->category;
        $data->sub_category = $request->sub_category;
        $data->hsn_sac_code = $request->hsn_sac_code;

        $data->gst_rate = $request->gst_rate;
        $data->base_uom = $request->base_uom;
        $data->purchase_uom = $request->purchase_uom;
        $data->conversion_factor = $request->conversion_factor;
        $data->recipe_uom = $request->recipe_uom;
        $data->recipe_conversion = $request->recipe_conversion;

        $data->standard_cost = $request->standard_cost;
        $data->mrp = $request->mrp;

        $data->min_stock_level = $request->min_stock_level;
        $data->max_stock_level = $request->max_stock_level;
        $data->reorder_quantity = $request->reorder_quantity;
        $data->lead_time_days = $request->lead_time_days;
        $data->shelf_life_days = $request->shelf_life_days;
        $data->wastage_allowance = $request->wastage_allowance;

        $data->storage_type = $request->storage_type;
        $data->storage_location = $request->storage_location;
        $data->primary_supplier_id = $request->primary_supplier_id;
        $data->alternate_supplier_id = $request->alternate_supplier_id;

        $data->perishable = $request->perishable ?? 0;
        $data->batch_tracked = $request->batch_tracked ?? 0;
        $data->active = $request->active ?? 1;

        $data->created_on = $request->created_on;
        $data->last_updated = $request->last_updated;
        $data->remarks = $request->remarks;

        $data->save();

        return redirect()->back()->with('success', 'Material saved successfully!');
    }

    // public function supplier_mast_form()
    // { //echo"a";die;
    //     $suppliers = SupplierMaster::orderByDesc('created_at')->paginate(20);

    //     return view('supplier.index', compact('suppliers'));
    // }

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
