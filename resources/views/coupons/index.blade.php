@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Coupon Codes</h5>

            <a href="{{ route('coupons.create') }}" class="btn btn-success btn-sm">
                + Add Coupon
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Coupon</th>
                            <th>Discount Code</th>
                            <th>Name</th>
                            <th>Limit</th>
                            <th>Used</th>
                            <th>Remaining</th>
                            <th>Once/Customer</th>
                            <th>Valid From</th>
                            <th>Valid To</th>
                            <th>Min Bill</th>
                            <th>Status</th>
                            <th>Channel</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($coupons as $c)
                        <tr>

                            <td><strong>{{ $c->coupon_code }}</strong></td>

                            <td>{{ $c->linked_discount_code ?? '-' }}</td>

                            <td>{{ $c->discount_name ?? '-' }}</td>

                            <td>{{ $c->usage_limit ?? 0 }}</td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $c->used_count ?? 0 }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $c->remaining }}
                                </span>
                            </td>

                            <td>
                                @if($c->once_per_customer)
                                    <span class="badge bg-warning text-dark">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>

                            <td>{{ $c->valid_from ?? '-' }}</td>

                            <td>{{ $c->valid_to ?? '-' }}</td>

                            <td>{{ $c->min_bill ?? 0 }}</td>

                            <td>
                                @if($c->status == 'Active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($c->status == 'Expired')
                                    <span class="badge bg-danger">Expired</span>
                                @else
                                    <span class="badge bg-secondary">{{ $c->status }}</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $c->channel }}
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="{{ route('coupons.edit', $c->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form action="{{ route('coupons.destroy', $c->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted py-4">
                                No coupons found
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