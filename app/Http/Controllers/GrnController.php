<?php

namespace App\Http\Controllers;

use App\Models\GrnHeader;
use App\Models\GrnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    public function index()
    {
        $grns = GrnHeader::with('items')->latest()->paginate(20);
        return view('grn.index', compact('grns'));
    }

    public function create()
    {
        return view('grn.create');
    }

    /**
     * Save GRN header + multiple material items in one transaction.
     */
    public function store(Request $request)
    {
        // ── 1. Validate Header ────────────────────────────────────────────────
        $headerData = $request->validate([
            'grn_no'           => 'required|string|max:50|unique:grn_headers,grn_no',
            'grn_date'         => 'required|date',
            'po_no'            => 'required|string|max:50',
            'invoice_no'       => 'required|string|max:100',
            'invoice_date'     => 'required|date',
            'supplier_id'      => 'required|string|max:50',
            'supplier_name'    => 'required|string|max:255',
            'storage_location' => 'required|string|max:255',
            'received_by'      => 'required|string|max:100',
            'verified_by'      => 'required|string|max:100',
            'payment_status'   => 'nullable|in:Unpaid,Partially Paid,Paid',
            'payment_date'     => 'nullable|date',
            'payment_reference'=> 'nullable|string|max:100',
            'remark'           => 'nullable|string|max:500',
        ]);

        // ── 2. Validate Item Rows ─────────────────────────────────────────────
        $itemRules = [
            'items'                               => 'required|array|min:1',
            'items.*.material_code'               => 'required|string|max:50',
            'items.*.material_name'               => 'required|string|max:255',
            'items.*.batch_lot_no'                => 'nullable|string|max:100',
            'items.*.mfg_date'                    => 'nullable|date',
            'items.*.expiry_date'                 => 'nullable|date',
            'items.*.qty_purchase_uom'            => 'required|numeric|min:0.0001',
            'items.*.purchase_uom'                => 'required|string|max:20',
            'items.*.conversion_factor'           => 'required|numeric|min:0.0001',
            'items.*.qty_base_uom'                => 'nullable|numeric|min:0',
            'items.*.base_uom'                    => 'nullable|string|max:20',
            'items.*.rate_per_purchase_uom'       => 'required|numeric|min:0',
            'items.*.taxable_value'               => 'nullable|numeric|min:0',
            'items.*.discount_percent'            => 'nullable|numeric|min:0|max:100',
            'items.*.discount_amount'             => 'nullable|numeric|min:0',
            'items.*.net_taxable_value'           => 'nullable|numeric|min:0',
            'items.*.gst_rate'                    => 'required|numeric|in:0,5,12,18,28',
            'items.*.cgst'                        => 'nullable|numeric|min:0',
            'items.*.sgst'                        => 'nullable|numeric|min:0',
            'items.*.igst'                        => 'nullable|numeric|min:0',
            'items.*.total_gst'                   => 'nullable|numeric|min:0',
            'items.*.other_charges'               => 'nullable|numeric|min:0',
            'items.*.round_off'                   => 'nullable|numeric',
            'items.*.total_amount'                => 'nullable|numeric|min:0',
            'items.*.effective_cost_per_base_uom' => 'nullable|numeric|min:0',
            'items.*.quality_check'               => 'required|in:Pass,Fail,Pending',
            'items.*.accepted_qty_base_uom'       => 'nullable|numeric|min:0',
            'items.*.rejected_qty_base_uom'       => 'nullable|numeric|min:0',
            'items.*.rejection_reason'            => 'nullable|string|max:500',
            'items.*.payment_status'              => 'nullable|in:Unpaid,Partially Paid,Paid',
            'items.*.payment_date'                => 'nullable|date',
            'items.*.payment_reference'           => 'nullable|string|max:100',
            'items.*.remark'                      => 'nullable|string|max:500',
        ];

        $itemMessages = [
            'items.required'                     => 'At least one material row is required.',
            'items.min'                          => 'At least one material row is required.',
            'items.*.material_code.required'     => 'Material Code is required in row :position.',
            'items.*.material_name.required'     => 'Material Name is required in row :position.',
            'items.*.qty_purchase_uom.required'  => 'Quantity is required in row :position.',
            'items.*.rate_per_purchase_uom.required' => 'Rate is required in row :position.',
            'items.*.gst_rate.required'          => 'GST Rate is required in row :position.',
            'items.*.quality_check.required'     => 'QC Status is required in row :position.',
        ];

        $request->validate($itemRules, $itemMessages);

        // ── 3. Recalculate all items server-side ──────────────────────────────
        $items = collect($request->input('items'))->map(function ($item) {
            return $this->recalcItem($item);
        })->toArray();

        // ── 4. Aggregate header totals from items ─────────────────────────────
        $headerData['total_taxable_value'] = collect($items)->sum('net_taxable_value');
        $headerData['total_gst_amount']    = collect($items)->sum('total_gst');
        $headerData['total_other_charges'] = collect($items)->sum('other_charges');
        $headerData['grand_total']         = collect($items)->sum('total_amount');
        $headerData['item_count']          = count($items);

        // ── 5. Persist in transaction ─────────────────────────────────────────
        DB::beginTransaction();
        try {
            $grn = GrnHeader::create($headerData);

            foreach ($items as $item) {
                $item['grn_header_id'] = $grn->id;
                $item['grn_no']        = $grn->grn_no;
                GrnItem::create($item);
            }

            DB::commit();

            return redirect()
                ->route('grn.show', $grn->id)
                ->with('success', "GRN #{$grn->grn_no} saved with " . count($items) . " material(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Save failed: ' . $e->getMessage());
        }
    }

    public function show(GrnHeader $grn)
    {
        $grn->load('items');
        return view('grn.show', compact('grn'));
    }

    public function edit(GrnHeader $grn)
    {
        $grn->load('items');
        return view('grn.edit', compact('grn'));
    }

    public function destroy(GrnHeader $grn)
    {
        DB::transaction(function () use ($grn) {
            $grn->items()->delete();
            $grn->delete();
        });
        return redirect()->route('grn.index')->with('success', "GRN #{$grn->grn_no} deleted.");
    }

    // ─── Private: Server-Side Row Calculation ─────────────────────────────────

    private function recalcItem(array $row): array
    {
        $qtyPur   = (float) ($row['qty_purchase_uom']      ?? 0);
        $conv     = (float) ($row['conversion_factor']     ?? 1) ?: 1;
        $rate     = (float) ($row['rate_per_purchase_uom'] ?? 0);
        $discPct  = (float) ($row['discount_percent']      ?? 0);
        $gstRate  = (float) ($row['gst_rate']              ?? 0);
        $igstIn   = (float) ($row['igst']                  ?? 0);
        $other    = (float) ($row['other_charges']         ?? 0);
        $roundOff = (float) ($row['round_off']             ?? 0);

        $qtyBase     = round($qtyPur * $conv, 4);
        $taxable     = round($qtyPur * $rate, 2);
        $discAmt     = round(($taxable * $discPct) / 100, 2);
        $netTaxable  = round($taxable - $discAmt, 2);

        if ($igstIn > 0) {
            $cgst     = 0;
            $sgst     = 0;
            $igst     = round($igstIn, 2);
            $totalGst = $igst;
        } else {
            $cgst     = round(($netTaxable * ($gstRate / 2)) / 100, 2);
            $sgst     = round(($netTaxable * ($gstRate / 2)) / 100, 2);
            $igst     = 0;
            $totalGst = round($cgst + $sgst, 2);
        }

        $totalAmt  = round($netTaxable + $totalGst + $other + $roundOff, 2);
        $effCost   = $qtyBase > 0 ? round($totalAmt / $qtyBase, 4) : 0;

        return array_merge($row, [
            'qty_base_uom'               => $qtyBase,
            'taxable_value'              => $taxable,
            'discount_amount'            => $discAmt,
            'net_taxable_value'          => $netTaxable,
            'cgst'                       => $cgst,
            'sgst'                       => $sgst,
            'igst'                       => $igst,
            'total_gst'                  => $totalGst,
            'total_amount'               => $totalAmt,
            'effective_cost_per_base_uom'=> $effCost,
        ]);
    }
}