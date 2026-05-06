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
        return view('supplier.create');
    }

    // ── Store (AJAX) ─────────────────────────────────────────────────────────
    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Normalise optional numeric fields to null when blank
        $nullableNumbers = ['opening_balance', 'credit_limit', 'min_order_value', 'discount_pct', 'payment_terms', 'lead_time_days', 'supplier_rating'];
        foreach ($nullableNumbers as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $supplier = SupplierMaster::create($validated);

        return response()->json([
            'success'      => true,
            'message'      => 'Supplier "' . $supplier->supp_name . '" saved successfully! Code: ' . $supplier->supp_code,
            'supplier_id'  => $supplier->id,
            'supp_code'    => $supplier->supp_code,
        ], 201);
    }

    // ── Show (AJAX or View) ──────────────────────────────────────────────────
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
        return view('supplier.edit', ['supplier' => $supplierMaster]);
    }

    // ── Update ───────────────────────────────────────────────────────────────
    public function update(StoreSupplierRequest $request, SupplierMaster $supplierMaster): JsonResponse
    {
        $validated = $request->validated();

        // If supp_code unchanged, ignore unique check on self
        // (handled below via custom rule override in UpdateSupplierRequest if needed)

        $supplierMaster->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Supplier "' . $supplierMaster->supp_name . '" updated successfully!',
        ]);
    }

    // ── Soft Delete ──────────────────────────────────────────────────────────
    public function destroy(SupplierMaster $supplierMaster): JsonResponse
    {
        $supplierMaster->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully.',
        ]);
    }

    // ── Search / Autocomplete (AJAX) ─────────────────────────────────────────
    public function search(Request $request): JsonResponse
    {
        $term = $request->input('q', '');

        $results = SupplierMaster::active()
            ->where(function ($query) use ($term) {
                $query->where('supp_name', 'like', "%{$term}%")
                      ->orWhere('supp_code', 'like', "%{$term}%")
                      ->orWhere('contact_no', 'like', "%{$term}%");
            })
            ->select('id', 'supp_name', 'supp_code', 'contact_no', 'supply_category')
            ->limit(15)
            ->get();

        return response()->json(['success' => true, 'data' => $results]);
    }
}