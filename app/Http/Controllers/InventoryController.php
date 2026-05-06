<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Item;

class InventoryController extends Controller
{

    public function ProcurementView()
    {
        $admin = Auth::guard('admin')->user();
        $role = $admin->role;

        $items = Item::whereNotNull('part_number')
                    ->where('part_number', '!=', '')
                    
                    ->get();

        return view('inventory.procurement', compact('items'));
    }

    public function CreatePo()
    {
        // echo"<pre>";die;
        $admin = Auth::guard('admin')->user();
        $role = $admin->role;
          return view('inventory.create_po'); // Pass orders
    }

    public function storePO(Request $request)
    {
        $request->validate([
            'items.0.part_number' => 'required',
            'items.0.order_qty' => 'required|numeric',
            'items.0.inv_unit_cost' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            $item = $request->items[0];

            $itemModel = new Item();

            $itemModel->supplier_name = $request->supplier_name;
            $itemModel->po_date       = $request->po_date;

            $itemModel->part_number      = $item['part_number'];
            $itemModel->part_description = $item['part_description'] ?? null;
            $itemModel->item_desc        = substr($item['part_description'] ?? 'NA', 0, 20);

            $itemModel->on_hand_qty   = $item['on_hand_qty'] ?? 0;
            $itemModel->inv_uom       = $item['inv_uom'] ?? null;
            $itemModel->inv_unit_cost = $item['inv_unit_cost'];
            $itemModel->order_qty     = $item['order_qty'];
            $itemModel->total_value   = $item['order_qty'] * $item['inv_unit_cost'];

            $itemModel->par_levels  = $item['par_levels'] ?? null;
            $itemModel->location    = $item['location'] ?? null;
            $itemModel->shelf       = $item['shelf'] ?? null;
            $itemModel->bin         = $item['bin'] ?? null;
            $itemModel->category    = $item['category'] ?? null;
            $itemModel->commodity   = $item['commodity'] ?? null;
            $itemModel->detail_code = $item['detail_code'] ?? null;

            // ✅ FIXED GROUP / REST
            $itemModel->group_code = '01';
            $itemModel->rest_code  = '01';

            // 🔥 CORRECT LAST NUMBER LOGIC
            $lastItem = Item::orderByRaw('CAST(item_code AS UNSIGNED) DESC')->first();

            if ($lastItem && $lastItem->item_code) {
                $nextNumber = (int)$lastItem->item_code + 1;
            } else {
                $nextNumber = 1;
            }

            // 🔥 3 digit format: 001, 002, 003
            $itemModel->item_code = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $itemModel->save();

            DB::commit();

            return back()->with('success', 'Item saved successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
        }
    }



}
