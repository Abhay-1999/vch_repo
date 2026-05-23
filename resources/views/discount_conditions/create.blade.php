@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h4 class="mb-0">Create Discount Conditions</h4>
            <a href="{{ route('discount-conditions.index') }}" class="btn btn-light btn-sm">
                Back
            </a>
        </div>

        <div class="card-body">

            <form action="{{ route('discount-conditions.store') }}" method="POST">
                @csrf


                <!-- DISCOUNT MASTER (ONCE ONLY) -->
                <div class="col-md-4 mb-3">
                    <label>Discount Code</label>

                    <select name="discount_id" id="discount_id" class="form-control">
                        <option value="">Select Discount</option>

                        @foreach($discountMasters as $discount)
                            <option value="{{ $discount->discount_id }}"
                                    data-name="{{ $discount->name }}">
                                {{ $discount->discount_id }} - {{ $discount->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- CONDITIONS ROWS -->
                <div id="rowsContainer">

                    <div class="row condition-row mb-3 p-2 border rounded">

                        <!-- Condition Type -->
                        <div class="col-md-2">
                            <label>Condition Type</label>
                            <select name="condition_type[]" class="form-control">
                                <option value="">Select</option>
                                <option value="MIN_BILL_VALUE">MIN_BILL_VALUE</option>
                                <option value="ITEM_CATEGORY">ITEM_CATEGORY</option>
                                <option value="DAY_OF_WEEK">DAY_OF_WEEK</option>
                                <option value="TIME_WINDOW">TIME_WINDOW</option>
                                <option value="LOYALTY_TIER">LOYALTY_TIER</option>
                                <option value="ORDER_COUNT">ORDER_COUNT</option>
                                <option value="CUSTOMER_AGE">CUSTOMER_AGE</option>
                                <option value="ORDER_CHANNEL">ORDER_CHANNEL</option>
                                <option value="PAYMENT_MODE">PAYMENT_MODE</option>
                            </select>
                        </div>

                        <!-- Operator -->
                        <div class="col-md-2">
                            <label>Operator</label>
                            <select name="operator[]" class="form-control">
                                <option value="">Select</option>
                                <option value="=">=</option>
                                <option value=">">></option>
                                <option value="<"><</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                                <option value="IN">IN</option>
                                <option value="NOT IN">NOT IN</option>
                                <option value="BETWEEN">BETWEEN</option>
                                <option value="LIKE">LIKE</option>
                            </select>
                        </div>

                        <!-- Value -->
                        <div class="col-md-3">
                            <label>Value</label>
                            <input type="text" name="value[]" class="form-control" placeholder="e.g. MON,TUE or 300">
                        </div>

                        <!-- Description -->
                        <div class="col-md-3">
                            <label>Description</label>
                            <input type="text" name="note[]" class="form-control" placeholder="Description">
                        </div>

                        <!-- REMOVE -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger removeRow">X</button>
                        </div>

                    </div>

                </div>

                <!-- ADD BUTTON -->
                <button type="button" id="addRow" class="btn btn-primary btn-sm mb-3">
                    + Add Condition
                </button>

                <!-- ACTIVE (SINGLE ONLY) -->
                <div class="col-md-3 mb-3">
                    <label>Active</label>
                    <select name="active" class="form-control">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <br>

                <button type="submit" class="btn btn-success">
                    Save Conditions
                </button>

            </form>

        </div>
    </div>

</div>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ADD ROW
    document.getElementById('addRow').addEventListener('click', function () {

        let row = document.querySelector('.condition-row');
        let clone = row.cloneNode(true);

        clone.querySelectorAll('input').forEach(i => i.value = '');
        clone.querySelectorAll('select').forEach(s => s.value = '');

        document.getElementById('rowsContainer').appendChild(clone);

    });

    // REMOVE ROW
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('removeRow')) {

            let rows = document.querySelectorAll('.condition-row');

            if (rows.length > 1) {
                e.target.closest('.condition-row').remove();
            }

        }

    });

});
</script>

@endsection