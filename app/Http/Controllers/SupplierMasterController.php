<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\SupplierMaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierMasterController extends Controller
{
    // ── List / Index ─────────────────────────────────────────────────────────
    public function index(): View
    {
        $suppliers = SupplierMaster::orderByDesc('created_at')->paginate(20);

        return view('supplier.index', compact('suppliers'));
    }

    // ── Create Form ──────────────────────────────────────────────────────────
    public function create(): View
    {
        $data = SupplierMaster::orderByDesc('created_at')->value('supplier_id');
        $suppId = '';
        if(!$data){
            $suppId = 'SUPP001';
        }else{
            $lastNumber = (int) substr($data, 4);

            $newNumber = $lastNumber + 1;

            $suppId =     'SUPP' .
            str_pad(
                $newNumber,
                3,
                '0',
                STR_PAD_LEFT
            );
        }
        return view('supplier.create',compact('suppId'));
    }

    // ── Store (AJAX) ─────────────────────────────────────────────────────────
    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Convert Blank Numeric Fields To NULL
        |--------------------------------------------------------------------------
        */

        $nullableNumbers = [
            'credit_limit',
            'tds_rate',
            'lead_time_days',
            'rating'
        ];

        foreach ($nullableNumbers as $field) {

            if (
                array_key_exists($field, $validated)
                && $validated[$field] === ''
            ) {
                $validated[$field] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Auto Default Values
        |--------------------------------------------------------------------------
        */

        $validated['currency'] = $validated['currency'] ?? 'INR';

        $validated['status'] = $validated['status'] ?? 'Active';

        /*
        |--------------------------------------------------------------------------
        | Create Supplier
        |--------------------------------------------------------------------------
        */

        $supplier = SupplierMaster::create($validated);

        return response()->json([

            'success' => true,

            'message' =>
                'Supplier "' .
                $supplier->supplier_name .
                '" saved successfully! Supplier ID: ' .
                $supplier->supplier_id,

            'supplier_id' => $supplier->id,

            'supplier_code' => $supplier->supplier_id,

        ], 201);
    }

    // ── Show ─────────────────────────────────────────────────────────────────
    public function show(SupplierMaster $supplierMaster): JsonResponse
    {
        return response()->json([
            'success'  => true,
            'supplier' => $supplierMaster,
        ]);
    }

    // ── Edit Form ────────────────────────────────────────────────────────────
    public function edit(SupplierMaster $supplierMaster): View
    {
        return view('supplier.edit', [
            'supplier' => $supplierMaster
        ]);
    }

    // ── Update ───────────────────────────────────────────────────────────────
    public function update(
        StoreSupplierRequest $request,
        SupplierMaster $supplierMaster
    ): JsonResponse {

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Nullable Numeric Fields
        |--------------------------------------------------------------------------
        */

        $nullableNumbers = [
            'credit_limit',
            'tds_rate',
            'lead_time_days',
            'rating'
        ];

        foreach ($nullableNumbers as $field) {

            if (
                array_key_exists($field, $validated)
                && $validated[$field] === ''
            ) {
                $validated[$field] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Supplier
        |--------------------------------------------------------------------------
        */

        $supplierMaster->update($validated);

        return response()->json([

            'success' => true,

            'message' =>
                'Supplier "' .
                $supplierMaster->supplier_name .
                '" updated successfully!',

        ]);
    }

    // ── Soft Delete ──────────────────────────────────────────────────────────
    public function destroy(
        SupplierMaster $supplierMaster
    ): JsonResponse {

        $supplierMaster->delete();

        return response()->json([

            'success' => true,

            'message' => 'Supplier deleted successfully.',

        ]);
    }

    // ── Search / Autocomplete ────────────────────────────────────────────────
    public function search(Request $request): JsonResponse
    {
        $term = $request->input('q', '');

        $results = SupplierMaster::where('status', 'Active')

            ->where(function ($query) use ($term) {

                $query->where('supplier_name', 'like', "%{$term}%")

                    ->orWhere('supplier_id', 'like', "%{$term}%")

                    ->orWhere('mobile_no', 'like', "%{$term}%")

                    ->orWhere('gstin', 'like', "%{$term}%");

            })

            ->select(
                'id',
                'supplier_id',
                'supplier_name',
                'mobile_no',
                'category',
                'gstin'
            )

            ->limit(15)

            ->get();

        return response()->json([

            'success' => true,

            'data' => $results

        ]);
    }
}