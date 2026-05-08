<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrnHeader;
use App\Models\GrnItem;
use DB;
use App\Models\RawMaterialMaster;
use App\Models\SupplierMaster;


class GrnController extends Controller
{
 
    public function index()
    {
        $grns = GrnHeader::latest()->paginate(10);

        return view('grn.index', compact('grns'));
    }

    public function create()
    {
        $lastGrn = GrnHeader::latest()->first();

        if ($lastGrn) {

            $lastNumber = (int) substr($lastGrn->grn_no, 3);

            $newNumber = $lastNumber + 1;

            $grn_no = 'GRN' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        } else {

            $grn_no = 'GRN001';
        }

        // MATERIALS
        $materials = RawMaterialMaster::select(
                        'material_code',
                        'material_name'
                    )->get();
            // SUPPLIERS
            $suppliers = SupplierMaster::select(
                            'supplier_id',
                            'supplier_name'
                        )->get();

// echo "<pre>";
// print_r($suppliers->toArray());
// die;
                                
        return view('grn.create', compact(
            'grn_no',
            'materials',
            'suppliers'
        ));
    }
    
    public function store(Request $request)
    {

        $request->validate([

        'grn_no'         => 'required|unique:grn_headers,grn_no',

        'grn_date'       => 'required|date',

        'invoice_no'     => 'required',

        'supplier_name'  => 'required',

        'material_code'  => 'required',

        'material_name'  => 'required',

        'purchase_qty'   => 'required|numeric|min:0',

        'purchase_uom'   => 'required',

        'rate'           => 'required|numeric|min:0',

        'total_amount'   => 'required|numeric|min:0',

    ], [

        'grn_no.required'        => 'GRN No is required',
        'grn_no.unique'          => 'GRN No already exists',

        'grn_date.required'      => 'GRN Date is required',

        'invoice_no.required'    => 'Invoice No is required',

        'supplier_name.required' => 'Supplier Name is required',

        'material_code.required' => 'Material Code is required',

        'material_name.required' => 'Material Name is required',

        'purchase_qty.required'  => 'Purchase Quantity is required',

        'purchase_uom.required'  => 'Purchase UOM is required',

        'rate.required'          => 'Rate is required',

        'total_amount.required'  => 'Total Amount is required',

    ]);

        DB::beginTransaction();

        try {

            $header = GrnHeader::create([

                'grn_no'               => $request->grn_no,
                'grn_date'             => $request->grn_date,
                'po_no'                => $request->po_no,

                'invoice_no'           => $request->invoice_no,
                'invoice_date'         => $request->invoice_date,

                'supplier_id'          => $request->supplier_id,
                'supplier_name'        => $request->supplier_name,

                'storage_location'     => $request->storage_location,
                'received_by'          => $request->received_by,
                'verified_by'          => $request->verified_by,

                'payment_status'       => $request->payment_status,
                'payment_date'         => $request->payment_date,
                'payment_reference'    => $request->payment_reference,

                'remark'               => $request->remarks,

                // totals
                'total_taxable_value'  => $request->taxable_value,
                'total_gst_amount'     => $request->total_gst,
                'total_other_charges'  => $request->other_charges,
                'grand_total'          => $request->total_amount,
                'item_count'           => 1,

            ]);

            GrnItem::create([

                'grn_header_id'                => $header->id,
                'grn_no'                       => $request->grn_no,

                'material_code'                => $request->material_code,
                'material_name'                => $request->material_name,

                'batch_lot_no'                 => $request->batch_no,

                'mfg_date'                     => $request->mfg_date,
                'expiry_date'                  => $request->expiry_date,

                'qty_purchase_uom'             => $request->purchase_qty,
                'purchase_uom'                 => $request->purchase_uom,

                'conversion_factor'            => $request->conversion_factor,

                'qty_base_uom'                 => $request->base_qty,
                'base_uom'                     => $request->base_uom,

                'rate_per_purchase_uom'        => $request->rate,

                'taxable_value'                => $request->taxable_value,

                'discount_percent'             => $request->discount_percent,
                'discount_amount'              => $request->discount_amount,

                'net_taxable_value'            => $request->net_taxable_value,

                'gst_rate'                     => $request->gst_rate,

                'cgst'                         => $request->cgst,
                'sgst'                         => $request->sgst,
                'igst'                         => $request->igst,

                'total_gst'                    => $request->total_gst,

                'other_charges'                => $request->other_charges,

                'round_off'                    => $request->round_off,

                'total_amount'                 => $request->total_amount,

                'effective_cost_per_base_uom' => $request->effective_cost,

                'quality_check'                => $request->quality_check,

                'accepted_qty_base_uom'        => $request->accepted_qty,

                'rejected_qty_base_uom'        => $request->rejected_qty,

                'rejection_reason'             => $request->rejection_reason,

                'payment_status'               => $request->payment_status,

                'payment_date'                 => $request->payment_date,

                'payment_reference'            => $request->payment_reference,

                'remark'                       => $request->remarks,

            ]);

            DB::commit();

        return redirect()
        ->route('grn.index')
        ->with('success', 'GRN Created Successfully');

        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $grn = GrnHeader::with('items')->findOrFail($id);

        return view('grn.show', compact('grn'));
    }

   
    public function edit($id)
    {
        $grn = GrnHeader::with('items')->findOrFail($id);

        return view('grn.edit', compact('grn'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $header = GrnHeader::findOrFail($id);

       
            $header->update([

                'grn_no'               => $request->grn_no,
                'grn_date'             => $request->grn_date,
                'po_no'                => $request->po_no,

                'invoice_no'           => $request->invoice_no,
                'invoice_date'         => $request->invoice_date,

                'supplier_id'          => $request->supplier_id,
                'supplier_name'        => $request->supplier_name,

                'storage_location'     => $request->storage_location,
                'received_by'          => $request->received_by,
                'verified_by'          => $request->verified_by,

                'payment_status'       => $request->payment_status,
                'payment_date'         => $request->payment_date,
                'payment_reference'    => $request->payment_reference,

                'remark'               => $request->remarks,

                'total_taxable_value'  => $request->taxable_value,
                'total_gst_amount'     => $request->total_gst,
                'total_other_charges'  => $request->other_charges,
                'grand_total'          => $request->total_amount,

            ]);


            $item = GrnItem::where('grn_header_id', $header->id)->first();

            if ($item) {

                $item->update([

                    'material_code'                => $request->material_code,
                    'material_name'                => $request->material_name,

                    'batch_lot_no'                 => $request->batch_no,

                    'mfg_date'                     => $request->mfg_date,
                    'expiry_date'                  => $request->expiry_date,

                    'qty_purchase_uom'             => $request->purchase_qty,
                    'purchase_uom'                 => $request->purchase_uom,

                    'conversion_factor'            => $request->conversion_factor,

                    'qty_base_uom'                 => $request->base_qty,
                    'base_uom'                     => $request->base_uom,

                    'rate_per_purchase_uom'        => $request->rate,

                    'taxable_value'                => $request->taxable_value,

                    'discount_percent'             => $request->discount_percent,
                    'discount_amount'              => $request->discount_amount,

                    'net_taxable_value'            => $request->net_taxable_value,

                    'gst_rate'                     => $request->gst_rate,

                    'cgst'                         => $request->cgst,
                    'sgst'                         => $request->sgst,
                    'igst'                         => $request->igst,

                    'total_gst'                    => $request->total_gst,

                    'other_charges'                => $request->other_charges,

                    'round_off'                    => $request->round_off,

                    'total_amount'                 => $request->total_amount,

                    'effective_cost_per_base_uom' => $request->effective_cost,

                    'quality_check'                => $request->quality_check,

                    'accepted_qty_base_uom'        => $request->accepted_qty,

                    'rejected_qty_base_uom'        => $request->rejected_qty,

                    'rejection_reason'             => $request->rejection_reason,

                    'payment_status'               => $request->payment_status,

                    'payment_date'                 => $request->payment_date,

                    'payment_reference'            => $request->payment_reference,

                    'remark'                       => $request->remarks,

                ]);
            }

            DB::commit();

            return redirect()
                ->route('grn.index')
                ->with('success', 'GRN Updated Successfully');

        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $header = GrnHeader::findOrFail($id);

        GrnItem::where('grn_header_id', $header->id)->delete();

        $header->delete();

        return redirect()
            ->route('grn.index')
            ->with('success', 'GRN Deleted Successfully');
    }
}