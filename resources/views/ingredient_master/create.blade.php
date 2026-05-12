@extends('auth.layouts.app')
@section('content')
<div class="container mt-4">
<div class="card shadow border-0">
   {{-- Header --}}
   <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
         <i class="fa fa-plus-circle me-2"></i>
         Add Ingredient Master
      </h4>
      <a href="{{ route('ingredient.index') }}" class="btn btn-light btn-sm">
      <i class="fa fa-arrow-left"></i> Back
      </a>
   </div>
   <div class="card-body">
      <form action="{{ route('ingredient.store') }}" method="POST">
         @csrf
         <div class="row">
            {{-- Ingredient Code --}}
            <div class="col-md-3 mb-3">
               <label class="form-label">
               Ingredient Code <span class="text-danger">*</span>
               </label>
               <input type="text"
                  name="ingredient_code"
                  class="form-control"
                  value="{{ $ingredient_code }}"
                  readonly>
            </div>
            {{-- Ingredient Name --}}
            <div class="col-md-5 mb-3">
               <label class="form-label">
               Ingredient Name <span class="text-danger">*</span>
               </label>
               <input type="text"
                  name="ingredient_name"
                  class="form-control @error('ingredient_name') is-invalid @enderror"
                  value="{{ old('ingredient_name') }}"
                  placeholder="Enter Ingredient Name">
               @error('ingredient_name')
               <div class="invalid-feedback">
                  {{ $message }}
               </div>
               @enderror
            </div>
            {{-- Category --}}
       <div class="col-md-4 mb-3">

    <label class="form-label">
        Category
    </label>

    <select name="category"
            class="form-control @error('category') is-invalid @enderror">

        <option value="">
            Select Category
        </option>

        <option value="Vegetable"
            {{ old('category') == 'Vegetable' ? 'selected' : '' }}>
            Vegetable
        </option>

        <option value="Dairy"
            {{ old('category') == 'Dairy' ? 'selected' : '' }}>
            Dairy
        </option>

        <option value="Meat"
            {{ old('category') == 'Meat' ? 'selected' : '' }}>
            Meat
        </option>

        <option value="Grocery"
            {{ old('category') == 'Grocery' ? 'selected' : '' }}>
            Grocery
        </option>

        <option value="Spice"
            {{ old('category') == 'Spice' ? 'selected' : '' }}>
            Spice
        </option>

        <option value="Packaging"
            {{ old('category') == 'Packaging' ? 'selected' : '' }}>
            Packaging
        </option>

    </select>

    @error('category')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>
            <div class="row">
               {{-- Purchase UOM --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Purchase UoM</label>
                  <select name="purchase_uom" class="form-control">
                     <option value="">Select UoM</option>
                     <option value="kg">KG</option>
                     <option value="gram">Gram</option>
                     <option value="liter">Liter</option>
                     <option value="ml">ML</option>
                     <option value="nos">Nos</option>
                  </select>
               </div>
               {{-- Purchase Qty --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Purchase Qty <span class="text-danger">*</span>
                  </label>
                  <input type="number"
                     step="0.001"
                     name="purchase_qty"
                     class="form-control @error('purchase_qty') is-invalid @enderror"
                     value="{{ old('purchase_qty') }}"
                     placeholder="0.000">
                  @error('purchase_qty')
                  <div class="invalid-feedback">
                     {{ $message }}
                  </div>
                  @enderror
               </div>
               {{-- Purchase Cost --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">
                  Purchase Cost (Rs) <span class="text-danger">*</span>
                  </label>
                  <input type="number"
                     step="0.01"
                     name="purchase_cost"
                     class="form-control @error('purchase_cost') is-invalid @enderror"
                     value="{{ old('purchase_cost') }}"
                     placeholder="0.00">
                  @error('purchase_cost')
                  <div class="invalid-feedback">
                     {{ $message }}
                  </div>
                  @enderror
               </div>
               {{-- Cost Per Purchase Unit --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Cost / Purchase Unit</label>
                  <input type="number"
                     name="cost_per_purchase_unit"
                     class="form-control"
                     readonly>
               </div>
            </div>
            <div class="row">
               {{-- Base UOM --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Base UoM</label>
                  <select name="base_uom" class="form-control">
                     <option value="">Select Base UoM</option>
                     <option value="kg">KG</option>
                     <option value="gram">Gram</option>
                     <option value="liter">Liter</option>
                     <option value="ml">ML</option>
                     <option value="nos">Nos</option>
                  </select>
               </div>
               {{-- Conversion To Base --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Conversion To Base</label>
                  <input type="number"
                     step="0.001"
                     name="conversion_to_base"
                     class="form-control"
                     value="{{ old('conversion_to_base') }}"
                     placeholder="Example: 1000">
               </div>
               {{-- Gross Cost Per Base --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Gross Cost/Base Unit</label>
                  <input type="number"
                     step="0.0001"
                     name="gross_cost_per_base_unit"
                     class="form-control"
                     value="{{ old('gross_cost_per_base_unit') }}"
                     placeholder="0.0000">
               </div>
               {{-- Yield Percent --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Yield %</label>
                  <input type="number"
                     step="0.01"
                     name="yield_percent"
                     class="form-control"
                     value="{{ old('yield_percent') }}"
                     placeholder="100">
               </div>
            </div>
            <div class="row">
               {{-- Net Cost --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Net Cost/Base Unit</label>
                  <input type="number"
                     step="any"
                     name="net_cost_per_base_unit"
                     class="form-control"
                     value="{{ old('net_cost_per_base_unit') }}"
                     placeholder="0.0000">
               </div>
               {{-- Wastage --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Wastage Allowance %</label>
                  <input type="number"
                     step="0.01"
                     name="wastage_allowance_percent"
                     class="form-control"
                     value="{{ old('wastage_allowance_percent') }}"
                     placeholder="0">
               </div>
               {{-- Costing Rate --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Costing Rate</label>
                  <input type="number"
                     step="any"
                     name="costing_rate"
                     class="form-control"
                     value="{{ old('costing_rate') }}"
                     placeholder="0.0000">
               </div>
               {{-- Supplier --}}
               <div class="col-md-3 mb-3">
                  <label class="form-label">Supplier</label>
                  <input type="text"
                     name="supplier"
                     class="form-control"
                     value="{{ old('supplier') }}"
                     placeholder="Enter Supplier">
               </div>
            </div>
            <div class="row">
               {{-- Last Updated --}}
               <div class="col-md-4 mb-3">
                  <label class="form-label">Last Updated</label>
                  <input type="datetime-local"
                     name="last_updated"
                     class="form-control"
                     value="{{ old('last_updated') }}">
               </div>
               {{-- Remarks --}}
               <div class="col-md-8 mb-3">
                  <label class="form-label">Remarks</label>
                  <textarea name="remarks"
                     rows="2"
                     class="form-control"
                     placeholder="Enter Remarks">{{ old('remarks') }}</textarea>
               </div>
            </div>
            {{-- Buttons --}}
            <div class="mt-3">
               <button type="submit" class="btn btn-success">
               <i class="fa fa-save"></i> Save Ingredient
               </button>
               <a href="{{ route('ingredient.index') }}"
                  class="btn btn-secondary">
               Cancel
               </a>
            </div>
      </form>
      </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function () {
   
       function calculateCosting() {
   
           let purchase_qty = parseFloat($('[name="purchase_qty"]').val()) || 0;
   
           let purchase_cost = parseFloat($('[name="purchase_cost"]').val()) || 0;
   
           let conversion_to_base = parseFloat($('[name="conversion_to_base"]').val()) || 0;
   
           let yield_percent = parseFloat($('[name="yield_percent"]').val()) || 0;
   
           let wastage_percent = parseFloat($('[name="wastage_allowance_percent"]').val()) || 0;
   
           // 1. Cost Per Purchase Unit
           let cost_per_purchase_unit = 0;
   
           if (purchase_qty > 0) {
               cost_per_purchase_unit = purchase_cost / purchase_qty;
           }
   
           $('[name="cost_per_purchase_unit"]')
               .val(cost_per_purchase_unit.toFixed(6));
   
           // 2. Gross Cost Per Base Unit
           let gross_cost_per_base_unit = 0;
   
           if (conversion_to_base > 0) {
               gross_cost_per_base_unit =
                   cost_per_purchase_unit / conversion_to_base;
           }
   
           $('[name="gross_cost_per_base_unit"]')
               .val(gross_cost_per_base_unit.toFixed(9));
   
           // 3. Net Cost Per Base Unit
           let net_cost_per_base_unit = 0;
   
           if (yield_percent > 0) {
   
               let yield_decimal = yield_percent / 100;
   
               net_cost_per_base_unit =
                   gross_cost_per_base_unit / yield_decimal;
           }
   
           $('[name="net_cost_per_base_unit"]')
               .val(net_cost_per_base_unit.toFixed(9));
   
           // 4. Costing Rate
           let costing_rate = net_cost_per_base_unit;
   
           if (wastage_percent > 0) {
   
               costing_rate =
                   net_cost_per_base_unit +
                   (net_cost_per_base_unit * wastage_percent / 100);
           }
   
           $('[name="costing_rate"]')
               .val(costing_rate.toFixed(9));
       }
   
       // Trigger on input
       $('input').on('keyup change', function () {
           calculateCosting();
       });
   
   });
   
</script>
@endsection