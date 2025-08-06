<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CustomerMaster;
use Illuminate\Support\Facades\DB;

class CustomerMasterController extends Controller
{
    public function cust_mast_form()
    {
        $custData = CustomerMaster::all();
        return view('customer.cust_list',compact('custData'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'address'    => 'required|string|max:100',
            'mob_no'     => 'required',
            'gst_no'     => 'nullable|string|max:15',
            'comp_name'  => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $action = $request->id ? 'updated' : 'created';

        CustomerMaster::updateOrCreate(
            ['id' => $request->id],
            $validator->validated()
        );

        return response()->json([
            'success' => true,
            'message' => "Customer $action successfully."
        ]);
    }

    public function edit($id)
    {
        $customer = CustomerMaster::findOrFail($id); 
        return response()->json(['customer' => $customer]);
    }

    public function destroy($id)
    {
        $customer = CustomerMaster::findOrFail($id);
        $customer->delete();
        return redirect()->route('cust_mast')->with('success', 'Customer deleted successfully!');
    }

    //discount setting

    public function disc_set_form()
    {
        $chainMaster = session('chainMaster', DB::table('chain_master')->first());
        return view('discount.disc_set', compact('chainMaster'));
    }


    public function updateDiscSet(Request $request)
    {
        DB::table('chain_master')->update([
            'zomato' => $request->zomato,
            'swiggy' => $request->swiggy,
        ]);

        $chainMaster = DB::table('chain_master')->first();

        return redirect()->route('disc_set_form')->with([
            'success' => 'Discount updated successfully!',
            'chainMaster' => $chainMaster,
        ]);
    }

}