@extends('auth.layouts.app')

@section('content')

<style>

    .table-responsive-custom{
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-custom table{
        min-width: 2500px;
        white-space: nowrap;
    }

    .table th{
        vertical-align: middle;
        text-align: center;
    }

    .table td{
        vertical-align: middle;
    }

</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">

        <h4>
            Current Stock Report
        </h4>

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif

    <div class="table-responsive-custom">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>#</th>

                    <th>Material Code</th>
                    <th>Material Name</th>
                    <th>Category</th>

                    <th>Base UoM</th>

                    <th>Opening Stock</th>
                    <th>Total Inward</th>
                    <th>Total Outward</th>
                    <th>Adjustments</th>
                    <th>Current Stock</th>

                    <th>Min Stock Level</th>
                    <th>Max Stock Level</th>

                    <th>Reorder Status</th>

                    <th>Std Cost (₹/Base UoM)</th>
                    <th>Stock Value (₹)</th>

                    <th>Last Purchase Date</th>
                    <th>Last Purchase Rate (₹)</th>

                    <th>Days Since Last Purchase</th>

                    <th>Storage Location</th>

                    <th>Shelf Life (Days)</th>

                    <th>Ageing Alert</th>

                    <th>Remarks</th>

                </tr>

            </thead>

            <tbody>

                @forelse($stocks as $key => $stock)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $stock->material_code }}</td>

                        <td>{{ $stock->material_name }}</td>

                        <td>{{ $stock->category }}</td>

                        <td>{{ $stock->base_uom }}</td>

                        {{-- STOCK DETAILS --}}
                        <td>{{ $stock->opening_stock ?? 0 }}</td>

                        <td>{{ $stock->total_inward ?? 0 }}</td>

                        <td>{{ $stock->total_outward ?? 0 }}</td>

                        <td>{{ $stock->adjustments ?? 0 }}</td>

                        <td>

                            <strong>
                                {{ $stock->standard_cost ?? 0 }}
                            </strong>

                        </td>

                        {{-- STOCK LEVEL --}}
                        <td>{{ $stock->min_stock_level }}</td>

                        <td>{{ $stock->max_stock_level }}</td>

                        {{-- REORDER STATUS --}}
                        <td>

                            @if(($stock->standard_cost ?? 0) <= ($stock->min_stock_level ?? 0))

                                <span class="badge bg-danger">
                                    Reorder Required
                                </span>

                            @else

                                <span class="badge bg-success">
                                    In Stock
                                </span>

                            @endif

                        </td>

                        {{-- COST --}}
                        <td>
                            ₹ {{ number_format($stock->standard_cost ?? 0, 2) }}
                        </td>

                        <td>

                            ₹ {{ number_format(($stock->current_stock ?? 0) * ($stock->standard_cost ?? 0), 2) }}

                        </td>

                        {{-- PURCHASE DETAILS --}}
                        <td>

                            {{ $stock->last_purchase_date ?? '-' }}

                        </td>

                        <td>

                            ₹ {{ number_format($stock->last_purchase_rate ?? 0, 2) }}

                        </td>

                        {{-- DAYS --}}
                        <td>

                            {{ $stock->days_since_last_purchase ?? '-' }}

                        </td>

                        {{-- STORAGE --}}
                        <td>{{ $stock->storage_location }}</td>

                        {{-- SHELF LIFE --}}
                        <td>{{ $stock->shelf_life_days }}</td>

                        {{-- AGEING ALERT --}}
                        <td>

                            @if(($stock->shelf_life_days ?? 0) <= 30)

                                <span class="badge bg-warning text-dark">
                                    Expiring Soon
                                </span>

                            @else

                                <span class="badge bg-success">
                                    Fresh
                                </span>

                            @endif

                        </td>

                        {{-- REMARKS --}}
                        <td>{{ $stock->remarks }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="22" class="text-center text-danger">

                            No Stock Records Found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection