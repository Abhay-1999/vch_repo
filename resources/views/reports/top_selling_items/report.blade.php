@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    {{-- HEADER --}}
    <div class="card shadow border-0">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-0">
                    Top Selling Items Report
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

                    <div class="card border-left-primary shadow-sm">

                        <div class="card-body">

                            <small class="text-muted">
                                Total Items
                            </small>

                            <h4 class="mb-0">

                                {{ count($reportData) }}

                            </h4>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card border-left-success shadow-sm">

                        <div class="card-body">

                            <small class="text-muted">
                                Report From
                            </small>

                            <h5 class="mb-0">

                                {{ $fromDate }}

                            </h5>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card border-left-info shadow-sm">

                        <div class="card-body">

                            <small class="text-muted">
                                Report To
                            </small>

                            <h5 class="mb-0">

                                {{ $toDate }}

                            </h5>

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
                                Item Code
                            </th>

                            <th class="text-end">
                                Qty
                            </th>

                            <th class="text-end">
                                Revenue
                            </th>

                            <th class="text-end">
                                Margin
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

                                    {{ $row->item_code }}

                                </strong>

                            </td>

                            <td class="text-end">

                                {{ number_format($row->total_qty) }}

                            </td>

                            <td class="text-end text-success">

                                ₹{{ number_format($row->total_revenue,2) }}

                            </td>

                            <td class="text-end text-primary">

                                ₹{{ number_format($row->total_margin,2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-muted py-4">

                                No records found

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