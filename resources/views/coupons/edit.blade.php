@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Coupon</h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('coupons.update', $coupon->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Coupon Code -->
                    <div class="col-md-4">
                        <label class="form-label">Coupon Code</label>
                        <input type="text"
                               name="coupon_code"
                               class="form-control"
                               value="{{ $coupon->coupon_code }}"
                               required>
                    </div>

                    <!-- Discount Select -->
                    <div class="col-md-4">
                        <label class="form-label">Discount Code</label>

                        <select name="linked_discount_code"
                                class="form-select"
                                id="discountSelect"
                                onchange="fillDiscountName()">

                            <option value="">Select</option>

                            @foreach($discounts as $discount)
                                <option value="{{ $discount->discount_id }}"
                                        data-name="{{ $discount->name }}"
                                        {{ $coupon->linked_discount_code == $discount->discount_id ? 'selected' : '' }}>
                                    {{ $discount->discount_id }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Coupon Name -->
                    <div class="col-md-4">
                        <label class="form-label">Coupon Name</label>

                        <input type="text"
                               name="discount_name"
                               id="discountName"
                               class="form-control"
                               value="{{ $coupon->discount_name }}"
                               placeholder="Coupon name">
                    </div>

                    <!-- Usage Limit -->
                    <div class="col-md-4">
                        <label class="form-label">Usage Limit</label>
                        <input type="number"
                               name="usage_limit"
                               class="form-control"
                               value="{{ $coupon->usage_limit }}">
                    </div>

                    <!-- Min Bill -->
                    <div class="col-md-4">
                        <label class="form-label">Minimum Bill</label>
                        <input type="number"
                               name="min_bill"
                               class="form-control"
                               value="{{ $coupon->min_bill }}">
                    </div>

                    <!-- Once Per Customer -->
                    <div class="col-md-4">
                        <label class="form-label">Once Per Customer</label>

                        <select name="once_per_customer" class="form-select">
                            <option value="0" {{ $coupon->once_per_customer == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $coupon->once_per_customer == 1 ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <!-- Valid From -->
                    <div class="col-md-4">
                        <label class="form-label">Valid From</label>
                        <input type="date"
                               name="valid_from"
                               class="form-control"
                               value="{{ $coupon->valid_from }}">
                    </div>

                    <!-- Valid To -->
                    <div class="col-md-4">
                        <label class="form-label">Valid To</label>
                        <input type="date"
                               name="valid_to"
                               class="form-control"
                               value="{{ $coupon->valid_to }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label">Status</label>

                        <select name="status" class="form-select">
                            <option value="Active" {{ $coupon->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Expired" {{ $coupon->status == 'Expired' ? 'selected' : '' }}>Expired</option>
                            <option value="Disabled" {{ $coupon->status == 'Disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>

                    <!-- Channel -->
                    <div class="col-md-4">
                        <label class="form-label">Channel</label>

                        <select name="channel" class="form-select">
                            <option value="All" {{ $coupon->channel == 'All' ? 'selected' : '' }}>All</option>
                            <option value="Online" {{ $coupon->channel == 'Online' ? 'selected' : '' }}>Online</option>
                            <option value="Dine-In" {{ $coupon->channel == 'Dine-In' ? 'selected' : '' }}>Dine-In</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-success px-4">
                        Update Coupon
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- JS for auto fill -->
<script>
function fillDiscountName() {

    let select = document.getElementById("discountSelect");
    let selectedOption = select.options[select.selectedIndex];

    let name = selectedOption.getAttribute("data-name");

    document.getElementById("discountName").value = name || '';
}
</script>

@endsection