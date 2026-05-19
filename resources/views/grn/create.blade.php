@extends('auth.layouts.app')
@section('content')
<style>
   .card-custom {
   border: none;
   border-radius: 12px;
   overflow: hidden;
   }
   .card-header-custom {
   background: linear-gradient(135deg, #343a40, #343a40);
   color: #fff;
   padding: 15px 20px;
   }
   .card-header-custom h4 {
   margin: 0;
   font-weight: 700;
   }
   .form-label {
   font-weight: 600;
   margin-bottom: 6px;
   }
   .form-control,
   .form-select {
   border-radius: 8px;
   height: 45px;
   }
   textarea.form-control {
   height: auto;
   }
   .btn-save {
   background: #198754;
   color: #fff;
   padding: 10px 25px;
   border-radius: 8px;
   font-weight: 600;
   border: none;
   }
   .btn-save:hover {
   background: #157347;
   color: #fff;
   }
   .section-title {
   font-size: 16px;
   font-weight: 700;
   color: #0d6efd;
   margin-top: 25px;
   margin-bottom: 15px;
   border-left: 4px solid #0d6efd;
   padding-left: 10px;
   }
</style>
<div class="container mt-4">
   <div class="card shadow card-custom">
      {{-- HEADER --}}
      <div class="card-header-custom d-flex justify-content-between align-items-center">
         <h4>
            <i class="fa fa-truck me-2"></i> GRN Entry Form
         </h4>
         <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
         <i class="fa fa-arrow-left"></i> Back
         </a>
      </div>
      <div class="card-body p-4">
         <form method="POST" action="{{ route('grn.store') }}">
            @csrf
            {{-- BASIC DETAILS --}}
            <div class="section-title">Basic Details</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">GRN No.</label>
                  <input type="text"
                     name="grn_no"
                     value="{{ old('grn_no', $grn_no) }}"
                     readonly
                     class="form-control @error('grn_no') is-invalid @enderror">
                  @error('grn_no')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">GRN Date</label>
                  <input type="date" name="grn_date" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">PO No.</label>
                  <input type="text" name="po_no" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Invoice No.</label>
                  <input type="text" name="invoice_no"
                     value="{{ old('invoice_no') }}"
                     class="form-control @error('invoice_no') is-invalid @enderror">
                  @error('invoice_no')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Invoice Date</label>
                  <input type="date" name="invoice_date" class="form-control">
               </div>
            </div>
            {{-- SUPPLIER DETAILS --}}
            <div class="section-title">Supplier Details</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Supplier ID</label>
                  <select name="supplier_id"
                     id="supplier_id"
                     class="form-select @error('supplier_id') is-invalid @enderror">
                     <option value="">Select Supplier</option>
                     @foreach($suppliers as $supplier)
                     <option value="{{ $supplier->supplier_id  }}"
                     data-name="{{ $supplier->supplier_name }}"
                     {{ old('supplier_id') == $supplier->supplier_id  ? 'selected' : '' }}>
                     {{ $supplier->supplier_id  }}
                     </option>
                     @endforeach
                  </select>
               </div>
               <div class="col-md-8 mb-3">
                  <label class="form-label">Supplier Name</label>
                  <input type="text"
                     name="supplier_name"
                     id="supplier_name"
                     value="{{ old('supplier_name') }}"
                     readonly
                     class="form-control @error('supplier_name') is-invalid @enderror">
                  @error('supplier_name')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
            </div>
            {{-- MATERIAL DETAILS --}}
            <div class="section-title">Material Details</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Material Code</label>
                  <select name="material_code"
                     id="material_code"
                     class="form-select @error('ingredient_code') is-invalid @enderror">
                     <option value="">Select Material</option>
                     @foreach($materials as $material)
                     <option value="{{ $material->ingredient_code }}"
                     data-name="{{ $material->ingredient_name }}"
                     {{ old('material_code') == $material->ingredient_code ? 'selected' : '' }}>
                     {{ $material->ingredient_code }}
                     </option>
                     @endforeach
                  </select>
                  @error('material_code')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-8 mb-3">
                  <label class="form-label">Material Name</label>
                  <input type="text"
                     name="material_name"
                     id="material_name"
                     value="{{ old('ingredient_name') }}"
                     readonly
                     class="form-control @error('ingredient_name') is-invalid @enderror">
                  @error('material_name')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Batch / Lot No.</label>
                  <input type="text" name="batch_no" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Mfg Date</label>
                  <input type="date" name="mfg_date" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Expiry Date</label>
                  <input type="date" name="expiry_date" class="form-control">
               </div>
            </div>
            {{-- QUANTITY DETAILS --}}
            <div class="section-title">Quantity Details</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Quantity (Purchase UoM)</label>
                  <input type="number" step="0.01" name="purchase_qty" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Purchase UoM</label>
                  <input type="text" name="purchase_uom"
                     value="{{ old('purchase_uom') }}"
                     class="form-control @error('purchase_uom') is-invalid @enderror">
                  @error('purchase_uom')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Conversion Factor</label>
                  <input type="number" step="0.01" name="conversion_factor" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Quantity (Base UoM)</label>
                  <input type="number" step="0.01" name="base_qty"
                     value="{{ old('base_qty') }}"
                     class="form-control @error('base_qty') is-invalid @enderror">
                  @error('base_qty')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Base UoM</label>
                  <input type="text" name="base_uom" class="form-control">
               </div>
            </div>
            {{-- TAX & AMOUNT --}}
            <div class="section-title">Tax & Amount</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Rate (₹ / Purchase UoM)</label>
                  <input type="number" step="0.01" name="rate"
                     value="{{ old('rate') }}"
                     class="form-control @error('rate') is-invalid @enderror">
                  @error('rate')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Taxable Value (₹)</label>
                  <input type="number" step="0.01" name="taxable_value" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Discount (%)</label>
                  <input type="number" step="0.01" name="discount_percent" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Discount Amount (₹)</label>
                  <input type="number" step="0.01" name="discount_amount" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Net Taxable Value (₹)</label>
                  <input type="number" step="0.01" name="net_taxable_value" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">GST Rate (%)</label>
                  <input type="number" step="0.01" name="gst_rate" class="form-control">
               </div>
               <div class="col-md-3 mb-3">
                  <label class="form-label">CGST (₹)</label>
                  <input type="number" step="0.01" name="cgst" class="form-control">
               </div>
               <div class="col-md-3 mb-3">
                  <label class="form-label">SGST (₹)</label>
                  <input type="number" step="0.01" name="sgst" class="form-control">
               </div>
               <div class="col-md-3 mb-3">
                  <label class="form-label">IGST (₹)</label>
                  <input type="number" step="0.01" name="igst" class="form-control">
               </div>
               <div class="col-md-3 mb-3">
                  <label class="form-label">Total GST (₹)</label>
                  <input type="number" step="0.01" name="total_gst" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Other Charges (₹)</label>
                  <input type="number" step="0.01" name="other_charges" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Round-off (₹)</label>
                  <input type="number" step="0.01" name="round_off" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Total Amount (₹)</label>
                  <input type="number" step="0.01" name="total_amount" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Effective Cost (₹/Base UoM)</label>
                  <input type="number" step="0.01" name="effective_cost" class="form-control">
               </div>
            </div>
            {{-- QUALITY CHECK --}}
            <div class="section-title">Quality Check</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Quality Check</label>
                  <select name="quality_check" class="form-select">
                     <option value="">Select</option>
                     <option value="Pass">Pass</option>
                     <option value="Fail">Fail</option>
                     <option value="Pending">Pending</option>
                  </select>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Accepted Qty (Base UoM)</label>
                  <input type="number" step="0.01" name="accepted_qty" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Rejected Qty (Base UoM)</label>
                  <input type="number" step="0.01" name="rejected_qty" class="form-control">
               </div>
               <div class="col-md-12 mb-3">
                  <label class="form-label">Rejection Reason</label>
                  <textarea name="rejection_reason" rows="3" class="form-control"></textarea>
               </div>
            </div>
            {{-- STORAGE & VERIFICATION --}}
            <div class="section-title">Storage & Verification</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Storage Location</label>
                  <input type="text" name="storage_location" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Received By</label>
                  <input type="text" name="received_by" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Verified By</label>
                  <input type="text" name="verified_by" class="form-control">
               </div>
            </div>
            {{-- PAYMENT DETAILS --}}
            <div class="section-title">Payment Details</div>
            <div class="row">
               <div class="col-md-4 mb-3">
                  <label class="form-label">Payment Status</label>
                  <select name="payment_status" class="form-select">
                     <option value="">Select</option>
                     <option value="Paid">Paid</option>
                     <option value="Pending">Pending</option>
                     <option value="Partial">Partial</option>
                  </select>
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Payment Date</label>
                  <input type="date" name="payment_date" class="form-control">
               </div>
               <div class="col-md-4 mb-3">
                  <label class="form-label">Payment Reference</label>
                  <input type="text" name="payment_reference" class="form-control">
               </div>
            </div>
            {{-- REMARKS --}}
            <div class="section-title">Remarks</div>
            <div class="row">
               <div class="col-md-12 mb-3">
                  <label class="form-label">Remarks</label>
                  <textarea name="remarks" rows="4" class="form-control"></textarea>
               </div>
            </div>
            {{-- SUBMIT BUTTON --}}
            <div class="mt-4">
               <button type="submit" class="btn btn-save">
               <i class="fa fa-save me-1"></i> Save GRN
               </button>
            </div>
         </form>
      </div>
   </div>
</div>
<script>
   document.getElementById('material_code').addEventListener('change', function () {
   
       let materialName = this.options[this.selectedIndex].getAttribute('data-name');
   
       document.getElementById('material_name').value = materialName ?? '';
   
   });
   // SUPPLIER AUTO FILL
   document.getElementById('supplier_id').addEventListener('change', function () {
   
       let supplierName = this.options[this.selectedIndex].getAttribute('data-name');
   
       document.getElementById('supplier_name').value = supplierName ?? '';
   
   });
   
</script>
@endsection