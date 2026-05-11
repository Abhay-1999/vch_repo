@extends('auth.layouts.app')

@section('content')

<style>
    .material-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .material-header {
        background: linear-gradient(135deg, #343a40, #343a40);
        color: #fff;
        padding: 18px 25px;
    }

    .material-header h4 {
        margin: 0;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        min-height: 45px;
        border: 1px solid #dcdcdc;
        box-shadow: none !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
    }

    textarea.form-control {
        min-height: 100px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0d6efd;
        border-left: 4px solid #0d6efd;
        padding-left: 10px;
        margin-bottom: 20px;
        margin-top: 10px;
    }

    .btn-custom {
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 600;
    }

    .card-body {
        background: #f8f9fa;
    }
</style>

<div class="container-fluid mt-4">

    <div class="card shadow material-card">

    {{-- Header --}}
<div class="material-header d-flex justify-content-between align-items-center">
    
    <h4 class="mb-0">
        <i class="fa fa-cubes me-2"></i> Material Master
    </h4>

    <a href="{{ route('raw_mat_index') }}" class="btn btn-light btn-sm">
        <i class="fa fa-arrow-left"></i> Back
    </a>

</div>

        {{-- Body --}}
        <div class="card-body">
        <form action="{{ route('raw_mat_store') }}" method="POST">
            @csrf
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
                {{-- BASIC DETAILS --}}
                <div class="section-title">
                    Basic Information
                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Material Code</label>
                           <input type="text"
                           name="material_code"
                                    class="form-control"
                                    value="{{ $auto_code ?? '' }}"
                                    readonly
                                    style="background:#f8f9fa;">
                        @error('material_code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Material Name</label>
                        <input type="text" name="material_name" class="form-control" placeholder="Enter Material Name">
                        @error('material_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">HSN / SAC Code</label>
                        <input type="text" name="hsn_sac_code" class="form-control" placeholder="Enter HSN Code">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
                    </div>

                <div class="col-md-3 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">Select Category</option>
                            <option value="Vegetables">Vegetables</option>
                            <option value="Fruits">Fruits</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Grains">Grains</option>
                            <option value="Spices">Spices</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Meat">Meat</option>
                            <option value="Seafood">Seafood</option>
                            <option value="Bakery">Bakery</option>
                            <option value="Frozen">Frozen</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sub Category</label>
                        <select name="sub_category" class="form-select">
                            <option value="">Select Sub Category</option>
                            <option value="Fresh">Fresh</option>
                            <option value="Organic">Organic</option>
                            <option value="Processed">Processed</option>
                            <option value="Raw">Raw</option>
                            <option value="Frozen Items">Frozen Items</option>
                            <option value="Dry Items">Dry Items</option>
                            <option value="Imported">Imported</option>
                            <option value="Local">Local</option>
                            <option value="Premium">Premium</option>
                            <option value="Standard">Standard</option>
                        </select>
                    </div>

                </div>

                {{-- UOM DETAILS --}}
                <div class="section-title">
                    Unit & Pricing Details
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">GST Rate (%)</label>
                        <input type="number" step="0.01" name="gst_rate" class="form-control">
                        @error('gst_rate')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Base UoM</label>
                        <input type="text" name="base_uom" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Purchase UoM</label>
                        <input type="text" name="purchase_uom" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Conversion Factor</label>
                        <input type="number" step="0.01" name="conversion_factor" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Recipe UoM</label>
                        <input type="text" name="recipe_uom" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Recipe Conversion</label>
                        <input type="number" step="0.01" name="recipe_conversion" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Standard Cost (₹)</label>
                        <input type="number" step="0.01" name="standard_cost" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">MRP (₹)</label>
                        <input type="number" step="0.01" name="mrp" class="form-control">
                        @error('mrp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                {{-- STOCK DETAILS --}}
                <div class="section-title">
                    Stock Management
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Min Stock Level</label>
                        <input type="number" step="0.01" name="min_stock_level" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Max Stock Level</label>
                        <input type="number" step="0.01" name="max_stock_level" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Reorder Quantity</label>
                        <input type="number" step="0.01" name="reorder_quantity" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lead Time (Days)</label>
                        <input type="number" name="lead_time_days" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Shelf Life (Days)</label>
                        <input type="number" name="shelf_life_days" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Wastage Allowance (%)</label>
                        <input type="number" step="0.01" name="wastage_allowance" class="form-control">
                    </div>

                </div>

                {{-- STORAGE DETAILS --}}
                <div class="section-title">
                    Storage & Supplier Details
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Storage Type</label>
                        <input type="text" name="storage_type" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Storage Location</label>
                        <input type="text" name="storage_location" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Primary Supplier ID</label>
                        <input type="text" name="primary_supplier_id" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Alternate Supplier ID</label>
                        <input type="text" name="alternate_supplier_id" class="form-control">
                    </div>

                </div>

                {{-- STATUS --}}
                <div class="section-title">
                    Status & Tracking
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Perishable</label>
                        <select name="perishable" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Batch Tracked</label>
                        <select name="batch_tracked" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Active Status</label>
                        <select name="active" class="form-select">
                            <option value="">Select</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Created On</label>
                        <input type="date" name="created_on" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Last Updated</label>
                        <input type="date" name="last_updated" class="form-control">
                    </div>

                    <div class="col-md-9 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" placeholder="Enter Remarks"></textarea>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="text-end mt-4">

                    <button type="reset" class="btn btn-danger btn-custom">
                        <i class="fa fa-refresh"></i> Reset
                    </button>

                    <button type="submit" class="btn btn-success btn-custom">
                        <i class="fa fa-save"></i> Save Material
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

@endsection