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
                <i class="fa fa-utensils me-2"></i> Recipe Mapping
            </h4>

            <a href="{{ route('recipe_mapping_index') }}" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body">

            <form action="{{ route('recipe_mapping_store') }}" method="POST">
                @csrf

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- BASIC INFO --}}
                <div class="section-title">Basic Information</div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Recipe ID</label>
                         <input type="text" name="recipe_id" class="form-control" value="{{ $recipe_id }}" readonly>
                          @error('recipe_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Item Code</label>
                       <select name="item_code" id="item_code" class="form-control">
                        <option value="">Select Item Code</option>

                        @foreach($items as $item)
                            <option 
                                value="{{ $item->item_code }}"
                                data-name="{{ $item->item_desc }}"
                            >
                                {{ $item->item_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Item Name</label>
                       <input type="text" name="item_desc" id="item_desc" class="form-control" readonly>
                       @error('item_desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="col-md-3 mb-3">
                        <label class="form-label">Selling Price (₹)</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Standard Yield (Servings)</label>
                        <input type="number" name="standard_yield" class="form-control">
                    </div>

                </div>

                {{-- MATERIAL INFO --}}
                <div class="section-title">Material Information</div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Material Code</label>
                        <select name="material_code" id="material_code" class="form-control">
                            <option value="">Select Material Code</option>

                            @foreach($materials as $material)
                                <option 
                                    value="{{ $material->material_code }}"
                                    data-name="{{ $material->material_name }}"
                                >
                                    {{ $material->material_code }}
                                </option>
                            @endforeach
                        </select>
                        @error('material_code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Material Name</label>
                           <input type="text" name="material_name" id="material_name" class="form-control" readonly>
                           @error('material_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Qty per Serving</label>
                        <input type="number" step="0.01" name="qty_per_serving" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Recipe UoM</label>
                        <input type="text" name="recipe_uom" class="form-control">
                    </div>

                </div>

                {{-- COST CALCULATION --}}
                <div class="section-title">Cost Calculation</div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Qty in Base UoM</label>
                        <input type="number" step="0.01" name="qty_in_base_uom" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Cost per Base UoM (₹)</label>
                        <input type="number" step="0.01" name="cost_per_base_uom" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ingredient Cost (₹)</label>
                        <input type="number" step="0.01" name="ingredient_cost" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Wastage (%)</label>
                        <input type="number" step="0.01" name="wastage_allowance" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Effective Cost (₹)</label>
                        <input type="number" step="0.01" name="effective_cost" class="form-control">
                    </div>

                </div>

                {{-- STATUS --}}
                <div class="section-title">Status & Tracking</div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Active</label>
                        <select name="active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Effective From</label>
                        <input type="date" name="effective_from" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Effective To</label>
                        <input type="date" name="effective_to" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Created By</label>
                        <select name="created_by" class="form-control">
                            <option value="">Select Created By</option>
                            <option value="Chef A">Chef A</option>
                            <option value="Chef B">Chef B</option>
                            <option value="Head Chef">Head Chef</option>
                            <option value="Manager">Manager</option>
                            <option value="Store Incharge">Store Incharge</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Approved By</label>
                          <select name="approved_by" class="form-control">
                            <option value="">Select Approved By</option>
                            <option value="Head Chef">Head Chef</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>

                    <div class="col-md-9 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="text-end mt-4">

                    <button type="reset" class="btn btn-danger btn-custom">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-success btn-custom">
                        Save Recipe Mapping
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<script>
document.getElementById('material_code').addEventListener('change', function () {
    let selectedOption = this.options[this.selectedIndex];

    let materialName = selectedOption.getAttribute('data-name');

    document.getElementById('material_name').value = materialName ?? '';
});

document.getElementById('item_code').addEventListener('change', function () {
    let selectedOption = this.options[this.selectedIndex];
    let itemName = selectedOption.getAttribute('data-name');

    document.getElementById('item_desc').value = itemName ?? '';
});
</script>
@endsection