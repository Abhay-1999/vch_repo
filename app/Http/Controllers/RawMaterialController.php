<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\Unit;

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
=======

    }

    public function create()
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

    public function store(Request $request)
    {
        RawMaterial::create([
            'material_name'=>$request->material_name,
            'material_code'=>$request->material_code,
            'unit_id'=>$request->unit_id,
            'opening_stock'=>$request->opening_stock,
            'current_stock'=>$request->opening_stock,
            'purchase_rate'=>$request->purchase_rate,
            'min_stock_alert'=>$request->min_stock_alert,
        ]);

        return redirect('admin/raw-materials');
    }
}
