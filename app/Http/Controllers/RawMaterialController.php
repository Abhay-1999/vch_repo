<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\Unit;

class RawMaterialController extends Controller
{
    public function index()
    {
        $materials = RawMaterial::with('unit')->get();

        return view('raw_material.index',compact('materials'));
    }

    public function create()
    {
        $units = Unit::get();

        return view('raw_material.create',compact('units'));
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
