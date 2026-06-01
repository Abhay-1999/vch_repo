@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    <div class="card shadow border-0">

        {{-- HEADER --}}
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-0">
                    Sales By Order Type
                </h4>

                <small class="text-light">

                    {{ $fromDate }}
                    to
                    {{ $toDate }}

                </small>

            </div>

        </div>


        <div class="card-body">

            {{-- SUMMARY --}}
            <div class="row mb-4">

                <div class="col-md-4">

                    <div class="card shadow-sm border-left-primary">

                        <div class="card-body">

                            <small class="text-muted">

                                Order Types

                            </small>

                            <h4 class="mb-0">

                                {{ count($reportData) }}

                            </h4>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card shadow-sm border-left-success">

                        <div class="card-body">

                            <small class="text-muted">

                                Total Orders

                            </small>

                            <h4 class="mb-0">

                                {{ $reportData->sum('total_orders') }}

                            </h4>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card shadow-sm border-left-info">

                        <div class="card-body">

                            <small class="text-muted">

                                Total Sales

                            </small>

                            <h4 class="mb-0 text-success">

                                ₹{{ number_format($reportData->sum('total_sales'),2) }}

                            </h4>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="bg-dark text-white">

                        <tr>

                            <th width="60">

                                #

                            </th>

                            <th>

                                Order Type

                            </th>

                            <th class="text-end">

                                Orders

                            </th>

                            <th class="text-end">

                                Sales

                            </th>

                            <th class="text-end">

                                % Share

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($reportData as $key => $row)

                        <tr>

                            <td>

                                {{ $key + 1 }}

                            </td>

                            <td>

                                <strong>

                                    {{ $row->order_type }}

                                </strong>

                            </td>

                            <td class="text-end">

                                {{ number_format($row->total_orders) }}

                            </td>

                            <td class="text-end text-success">

                                ₹{{ number_format($row->total_sales,2) }}

                            </td>

                            <td class="text-end">

    <span class="badge bg-primary text-white">

        {{ isset($row->share_percent)
            ? number_format($row->share_percent, 2)
            : '0.00' }}%

    </span>

</td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted py-4">

                                No data found

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