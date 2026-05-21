@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create Coupon</h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('coupons.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Coupon Code</label>
                        <input type="text" name="coupon_code" class="form-control" placeholder="Enter coupon code" required>
                    </div>

                   <div class="col-md-4">
    <label class="form-label">Discount Code</label>

    <select name="linked_discount_code"
            class="form-select"
            id="discountSelect"
            onchange="fillDiscountName()">

        <option value="">Select</option>

        @foreach($discounts as $discount)
            <option value="{{ $discount->discount_id }}"
                    data-name="{{ $discount->name }}">
                {{ $discount->discount_id }}
            </option>
        @endforeach

    </select>
</div>

                  <div class="col-md-4">
    <label class="form-label">Coupon Name</label>

    <input type="text"
           name="discount_name"
           id="discountName"
           class="form-control"
           placeholder="Coupon name">
</div>

                    <div class="col-md-4">
                        <label class="form-label">Usage Limit</label>
                        <input type="number" name="usage_limit" class="form-control" placeholder="e.g. 100">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Minimum Bill</label>
                        <input type="number" name="min_bill" class="form-control" placeholder="e.g. 500">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Once Per Customer</label>
                        <select name="once_per_customer" class="form-select">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valid From</label>
                        <input type="date" name="valid_from" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Valid To</label>
                        <input type="date" name="valid_to" class="form-control">
                    </div>
                    

                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Expired">Expired</option>
                            <option value="Disabled">Disabled</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Channel</label>
                        <select name="channel" class="form-select">
                            <option value="All">All</option>
                            <option value="Online">Online</option>
                            <option value="Dine-In">Dine-In</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-success px-4">Save Coupon</button>
                </div>

            </form>

        </div>
    </div>

</div>
<script>
function fillDiscountName() {

    let select = document.getElementById("discountSelect");
    let selectedOption = select.options[select.selectedIndex];

    let name = selectedOption.getAttribute("data-name");

    document.getElementById("discountName").value = name || '';
}
</script>
@endsection