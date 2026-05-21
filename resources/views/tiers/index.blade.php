@extends('auth.layouts.app')

@section('content')

<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Tier Management</h3>
            <p class="text-muted mb-0">Manage customer membership tiers</p>
        </div>

        <a href="{{ route('tiers.create') }}" class="btn btn-primary shadow-sm">
            <i class="fa fa-plus me-1"></i> Add Tier
        </a>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 pb-3">
            <h5 class="mb-0 fw-semibold">
                <i class="fa fa-layer-group text-primary me-2"></i>
                Tier List
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle table-hover">

                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">Tier ID</th>
                            <th class="fw-semibold">Tier Name</th>
                            <th class="fw-semibold">Min Spend</th>
                            <th class="fw-semibold">Max Spend</th>
                            <th class="fw-semibold">Discount</th>
                            <th class="fw-semibold">Status</th>
                            <th class="fw-semibold text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($tiers as $tier)

                        <tr>

                            <td>
                                <span class="badge bg-dark px-3 py-2">
                                    {{ $tier->tier_id }}
                                </span>
                            </td>

                            <td class="fw-semibold text-dark">
                                {{ $tier->tier_name }}
                            </td>

                            <td>
                                ₹ {{ number_format($tier->min_lifetime_spend, 2) }}
                            </td>

                            <td>
                                ₹ {{ number_format($tier->max_lifetime_spend, 2) }}
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $tier->auto_discount_percent }}%
                                </span>
                            </td>

                            <td>
                                @if($tier->status == 'Active')
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">

                                <a href="{{ route('tiers.edit', $tier->id) }}"
                                   class="btn btn-sm btn-warning rounded-pill px-3">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form action="{{ route('tiers.destroy', $tier->id) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger rounded-pill px-3"
                                            onclick="return confirm('Are you sure to delete this tier?')">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No Tier Found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection