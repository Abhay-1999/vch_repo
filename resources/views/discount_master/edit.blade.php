@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        {{-- Header --}}
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                Edit Discount Master
            </h4>

            <a href="{{ route('discount-master.index') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        {{-- Body --}}
        <div class="card-body">

            <form action="{{ route('discount-master.update', $discount->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Discount Code --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Discount Code
                        </label>

                        <input type="text"
                               name="discount_id"
                               class="form-control"
                               value="{{ $discount->discount_id }}"
                               readonly>

                    </div>

                    {{-- Discount Name --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Discount Name
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name', $discount->name) }}"
                               class="form-control @error('name') is-invalid @enderror"
                               maxlength="80">

                        @error('name')

                            <small class="text-danger">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>

                    {{-- Discount Type --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Type
                        </label>

                        <select name="type"
                                class="form-control @error('type') is-invalid @enderror">

                            <option value="">Select</option>

                            @php

                                $types = [
                                    'FLAT_PCT',
                                    'MIN_BILL',
                                    'HAPPY_HOUR',
                                    'BIRTHDAY',
                                    'COUPON_PCT',
                                    'BOGO',
                                    'LOYALTY',
                                    'STAFF',
                                    'BULK',
                                    'AGGREGATOR',
                                    'OPEN',
                                    'COMBO'
                                ];

                            @endphp

                            @foreach($types as $type)

                                <option value="{{ $type }}"
                                    {{ old('type', $discount->type) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                        @error('type')

                            <small class="text-danger">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>

                    {{-- Discount Value --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Value
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="value"
                               value="{{ old('value', $discount->value) }}"
                               class="form-control @error('value') is-invalid @enderror">

                        @error('value')

                            <small class="text-danger">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>

                    {{-- Unit --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Value Unit
                        </label>

                        <select name="unit"
                                class="form-control @error('unit') is-invalid @enderror">

                            <option value="%"
                                {{ old('unit', $discount->unit) == '%' ? 'selected' : '' }}>
                                %
                            </option>

                            <option value="Rs"
                                {{ old('unit', $discount->unit) == 'Rs' ? 'selected' : '' }}>
                                Rs
                            </option>

                            <option value="qty"
                                {{ old('unit', $discount->unit) == 'qty' ? 'selected' : '' }}>
                                qty
                            </option>

                        </select>

                    </div>

                    {{-- Max Cap --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Max Cap (Rs)
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="max_cap"
                               value="{{ old('max_cap', $discount->max_cap) }}"
                               class="form-control">

                    </div>

                    {{-- Min Bill --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Min Bill (Rs)
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="min_bill"
                               value="{{ old('min_bill', $discount->min_bill) }}"
                               class="form-control">

                    </div>

                    {{-- Applies To --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Applies To
                        </label>

                        <select name="applies_to"
                                class="form-control">

                            <option value="Bill Total"
                                {{ old('applies_to', $discount->applies_to) == 'Bill Total' ? 'selected' : '' }}>

                                Bill Total

                            </option>

                            <optgroup label="Items">

                                @foreach($items as $item)

                                    <option value="Item: {{ $item->item_name }}"
                                        {{ old('applies_to', $discount->applies_to) == 'Item: '.$item->item_name ? 'selected' : '' }}>

                                        Item: {{ $item->item_name }}

                                    </option>

                                @endforeach

                            </optgroup>

                        </select>

                    </div>

                    {{-- Stackable --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Stackable
                        </label>

                        <select name="stackable"
                                class="form-control">

                            <option value="1"
                                {{ old('stackable', $discount->stackable) == 1 ? 'selected' : '' }}>

                                Yes

                            </option>

                            <option value="0"
                                {{ old('stackable', $discount->stackable) == 0 ? 'selected' : '' }}>

                                No

                            </option>

                        </select>

                    </div>

                    {{-- Auto Apply --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Auto Apply
                        </label>

                        <select name="auto_apply"
                                class="form-control">

                            <option value="1"
                                {{ old('auto_apply', $discount->auto_apply) == 1 ? 'selected' : '' }}>

                                Yes

                            </option>

                            <option value="0"
                                {{ old('auto_apply', $discount->auto_apply) == 0 ? 'selected' : '' }}>

                                No

                            </option>

                        </select>

                    </div>

                    {{-- Approval Req --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Approval Req
                        </label>

                        <select name="approval_req"
                                class="form-control">

                            <option value="0"
                                {{ old('approval_req', $discount->approval_req) == 0 ? 'selected' : '' }}>

                                No

                            </option>

                            <option value="1"
                                {{ old('approval_req', $discount->approval_req) == 1 ? 'selected' : '' }}>

                                Yes

                            </option>

                        </select>

                    </div>

                    {{-- Approval Level --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Approval Level
                        </label>

                        <select name="approval_level"
                                class="form-control">

                            @php

                                $levels = ['Cashier', 'Manager', 'Admin', 'Auto'];

                            @endphp

                            @foreach($levels as $level)

                                <option value="{{ $level }}"
                                    {{ old('approval_level', $discount->approval_level) == $level ? 'selected' : '' }}>

                                    {{ $level }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Valid From --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Valid From
                        </label>

                        <input type="date"
                               name="valid_from"
                               value="{{ old('valid_from', $discount->valid_from) }}"
                               class="form-control">

                    </div>

                    {{-- Valid To --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Valid To
                        </label>

                        <input type="date"
                               name="valid_to"
                               value="{{ old('valid_to', $discount->valid_to) }}"
                               class="form-control">

                    </div>

                    {{-- Active Days --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Active Days
                        </label>

                        <input type="text"
                               name="active_days"
                               value="{{ old('active_days', $discount->active_days) }}"
                               class="form-control">

                    </div>

                    {{-- Active Hours --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Active Hours
                        </label>

                        <input type="text"
                               name="active_hours"
                               value="{{ old('active_hours', $discount->active_hours) }}"
                               class="form-control">

                    </div>

                    {{-- Channel --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Channel
                        </label>

                        <input type="text"
                               name="channel"
                               value="{{ old('channel', $discount->channel) }}"
                               class="form-control">

                    </div>

                    {{-- Outlet --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Outlet Scope
                        </label>

                        <input type="text"
                               name="outlet"
                               value="{{ old('outlet', $discount->outlet) }}"
                               class="form-control">

                    </div>

                    {{-- Status --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-control">

                            <option value="Active"
                                {{ old('status', $discount->status) == 'Active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="Inactive"
                                {{ old('status', $discount->status) == 'Inactive' ? 'selected' : '' }}>

                                Inactive

                            </option>

                        </select>

                    </div>

                    {{-- Remarks --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea name="remarks"
                                  rows="3"
                                  maxlength="160"
                                  class="form-control">{{ old('remarks', $discount->remarks) }}</textarea>

                    </div>

                    {{-- Submit --}}
                    <div class="col-md-12 text-center">

                        <button type="submit"
                                class="btn btn-primary px-5">

                            Update Discount

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection