@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow border-0">

        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Loyalty Tier</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('tiers.update', $tier->id) }}">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tier Name</label>
                        <input type="text" 
                               name="tier_name" 
                               class="form-control"
                               value="{{ $tier->tier_name }}"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Min Lifetime Spend</label>
                        <input type="number" 
                               name="min_lifetime_spend" 
                               class="form-control"
                               value="{{ $tier->min_lifetime_spend }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Max Lifetime Spend</label>
                        <input type="number" 
                               name="max_lifetime_spend" 
                               class="form-control"
                               value="{{ $tier->max_lifetime_spend }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Auto Discount %</label>
                        <input type="number" 
                               step="0.01"
                               name="auto_discount_percent" 
                               class="form-control"
                               value="{{ $tier->auto_discount_percent }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Discount Cap</label>
                        <input type="number" 
                               step="0.01"
                               name="discount_cap" 
                               class="form-control"
                               value="{{ $tier->discount_cap }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Birthday Bonus %</label>
                        <input type="number" 
                               step="0.01"
                               name="birthday_bonus_percent" 
                               class="form-control"
                               value="{{ $tier->birthday_bonus_percent }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Reward Points Per 100</label>
                        <input type="number" 
                               name="reward_points_per_100" 
                               class="form-control"
                               value="{{ $tier->reward_points_per_100 }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Linked Discount Code</label>
                        <select name="linked_discount_code" class="form-select">
                            <option value="">Select</option>
                            @foreach($discounts as $discount)
                                <option value="{{ $discount->discount_id }}"
                                        data-name="{{ $discount->name }}"
                                        {{ $tier->linked_discount_code == $discount->discount_id ? 'selected' : '' }}>
                                    {{ $discount->discount_id }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" 
                               name="color" 
                               class="form-control form-control-color"
                               value="{{ $tier->color }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="Active" 
                                {{ $tier->status == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ $tier->status == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" 
                                  class="form-control" 
                                  rows="3">{{ $tier->remarks }}</textarea>
                    </div>

                </div>

                <div class="mt-3">

                    <button type="submit" class="btn btn-primary">
                        Update Tier
                    </button>

                    <a href="{{ route('tiers.index') }}" 
                       class="btn btn-secondary">
                        Back
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection