@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Coupon Usage Report
            </h5>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <strong>From:</strong>
                {{ $fromDate }}

                <span class="mx-2">|</span>

                <strong>To:</strong>
                {{ $toDate }}

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Coupon Code
                            </th>

                            <th>
                                Discount Type
                            </th>

                            <th>
                                Discount Name
                            </th>

                            <th class="text-end">
                                Usage Limit
                            </th>

                            <th class="text-end">
                                Used
                            </th>

                            <th class="text-end">
                                Remaining
                            </th>

                            <th class="text-end">
                                Redeem %
                            </th>

                            <th class="text-end">
                                Orders
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($coupons as $row)

                            <tr>

                                <td>
                                    {{ $row->coupon_code }}
                                </td>

                                <td>
                                    {{ $row->discount_type ?? '-' }}
                                </td>

                                <td>
                                    {{ $row->discount_name ?? '-' }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->usage_limit) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->used_count) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->remaining) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->redeem_percent, 2) }}%
                                </td>

                                <td class="text-end">
                                    {{ number_format($row->total_orders) }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center text-danger">

                                    No records found.

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