@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between">

            <h4 class="mb-0">
                Edit Discount Conditions
            </h4>

            <a href="{{ route('discount-conditions.index') }}"
               class="btn btn-light btn-sm">
                Back
            </a>

        </div>

        <div class="card-body">

            <form action="{{ route('discount-conditions.update', $conditions->first()->condition_id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <!-- DISCOUNT -->
                <div class="col-md-4 mb-3">

                    <label>Discount Code</label>

                    <select name="discount_id"
                            class="form-control">

                        <option value="">
                            Select Discount
                        </option>

                        @foreach($discountMasters as $discount)

                            <option value="{{ $discount->discount_id }}"
                                {{ $conditions->first()->discount_id == $discount->discount_id ? 'selected' : '' }}>

                                {{ $discount->discount_id }}
                                -
                                {{ $discount->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- ROWS -->
                <div id="rowsContainer">

                    @foreach($conditions as $condition)

                    <div class="row condition-row mb-3 p-2 border rounded">

                        <!-- CONDITION TYPE -->
                        <div class="col-md-2">

                            <label>Condition Type</label>

                            <select name="condition_type[]"
                                    class="form-control">

                                <option value="">
                                    Select
                                </option>

                                <option value="MIN_BILL_VALUE"
                                    {{ $condition->condition_type == 'MIN_BILL_VALUE' ? 'selected' : '' }}>
                                    MIN_BILL_VALUE
                                </option>

                                <option value="ITEM_CATEGORY"
                                    {{ $condition->condition_type == 'ITEM_CATEGORY' ? 'selected' : '' }}>
                                    ITEM_CATEGORY
                                </option>

                                <option value="DAY_OF_WEEK"
                                    {{ $condition->condition_type == 'DAY_OF_WEEK' ? 'selected' : '' }}>
                                    DAY_OF_WEEK
                                </option>

                                <option value="TIME_WINDOW"
                                    {{ $condition->condition_type == 'TIME_WINDOW' ? 'selected' : '' }}>
                                    TIME_WINDOW
                                </option>

                                <option value="LOYALTY_TIER"
                                    {{ $condition->condition_type == 'LOYALTY_TIER' ? 'selected' : '' }}>
                                    LOYALTY_TIER
                                </option>

                                <option value="ORDER_COUNT"
                                    {{ $condition->condition_type == 'ORDER_COUNT' ? 'selected' : '' }}>
                                    ORDER_COUNT
                                </option>

                                <option value="CUSTOMER_AGE"
                                    {{ $condition->condition_type == 'CUSTOMER_AGE' ? 'selected' : '' }}>
                                    CUSTOMER_AGE
                                </option>

                                <option value="ORDER_CHANNEL"
                                    {{ $condition->condition_type == 'ORDER_CHANNEL' ? 'selected' : '' }}>
                                    ORDER_CHANNEL
                                </option>

                                <option value="PAYMENT_MODE"
                                    {{ $condition->condition_type == 'PAYMENT_MODE' ? 'selected' : '' }}>
                                    PAYMENT_MODE
                                </option>

                            </select>

                        </div>

                        <!-- OPERATOR -->
                        <div class="col-md-2">

                            <label>Operator</label>

                            <select name="operator[]"
                                    class="form-control">

                                <option value="">Select</option>

                                <option value="="
                                    {{ $condition->operator == '=' ? 'selected' : '' }}>
                                    =
                                </option>

                                <option value=">"
                                    {{ $condition->operator == '>' ? 'selected' : '' }}>
                                    >
                                </option>

                                <option value="<"
                                    {{ $condition->operator == '<' ? 'selected' : '' }}>
                                    <
                                </option>

                                <option value=">="
                                    {{ $condition->operator == '>=' ? 'selected' : '' }}>
                                    >=
                                </option>

                                <option value="<="
                                    {{ $condition->operator == '<=' ? 'selected' : '' }}>
                                    <=
                                </option>

                                <option value="IN"
                                    {{ $condition->operator == 'IN' ? 'selected' : '' }}>
                                    IN
                                </option>

                                <option value="NOT IN"
                                    {{ $condition->operator == 'NOT IN' ? 'selected' : '' }}>
                                    NOT IN
                                </option>

                                <option value="BETWEEN"
                                    {{ $condition->operator == 'BETWEEN' ? 'selected' : '' }}>
                                    BETWEEN
                                </option>

                                <option value="LIKE"
                                    {{ $condition->operator == 'LIKE' ? 'selected' : '' }}>
                                    LIKE
                                </option>

                            </select>

                        </div>

                        <!-- VALUE -->
                        <div class="col-md-3">

                            <label>Value</label>

                            <input type="text"
                                   name="value[]"
                                   class="form-control"
                                   value="{{ $condition->value }}">

                        </div>

                        <!-- NOTE -->
                        <div class="col-md-3">

                            <label>Description</label>

                            <input type="text"
                                   name="note[]"
                                   class="form-control"
                                   value="{{ $condition->note }}">

                        </div>

                        <!-- REMOVE -->
                        <div class="col-md-1 d-flex align-items-end">

                            <button type="button"
                                    class="btn btn-danger removeRow">
                                X
                            </button>

                        </div>

                    </div>

                    @endforeach

                </div>

                <!-- ADD ROW -->
                <button type="button"
                        id="addRow"
                        class="btn btn-primary btn-sm mb-3">

                    + Add Condition

                </button>

                <!-- ACTIVE -->
                <div class="col-md-3 mb-3">

                    <label>Active</label>

                    <select name="active"
                            class="form-control">

                        <option value="1"
                            {{ $conditions->first()->active == 1 ? 'selected' : '' }}>
                            Yes
                        </option>

                        <option value="0"
                            {{ $conditions->first()->active == 0 ? 'selected' : '' }}>
                            No
                        </option>

                    </select>

                </div>

                <br>

                <button type="submit"
                        class="btn btn-success">

                    Update Conditions

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