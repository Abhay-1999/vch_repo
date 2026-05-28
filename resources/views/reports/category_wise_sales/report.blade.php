@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Category-wise Sales Report

            </h5>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                Sales grouped category-wise with
                quantity, gross revenue,
                discount, net revenue and sales share.

            </div>

            <div class="mb-3">


            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Category</th>

                            <th class="text-end">
                                Qty Sold
                            </th>

                            <th class="text-end">
                                Gross Revenue
                            </th>

                            <th class="text-end">
                                Discount
                            </th>

                            <th class="text-end">
                                Net Revenue
                            </th>

                            <th class="text-end">
                                % of Total Sales
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $rank = 1;
                        @endphp

                        @forelse($categories as $row)

                        <tr>

                            <td>
                                {{ $rank++ }}
                            </td>

                            <td>

                                {{ $row->category ?? 'N/A' }}

                            </td>

                            <td class="text-end">

                                {{ number_format($row->total_qty) }}

                            </td>

                            <td class="text-end">

                                {{ number_format($row->gross_revenue, 2) }}

                            </td>

                            <td class="text-end">

                                {{ number_format($row->discount, 2) }}

                            </td>

                            <td class="text-end fw-bold text-primary">

                                {{ number_format($row->net_revenue, 2) }}

                            </td>

                            <td class="text-end">

                                {{ number_format($row->sales_percent, 1) }}%

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-danger">

                                No records found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                    <tfoot class="table-warning">

                        <tr>

                            <th colspan="2">

                                TOTAL

                            </th>

                            <th class="text-end">

                                {{ number_format($grandQty) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($grandGross, 2) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($grandDiscount, 2) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($grandNet, 2) }}

                            </th>

                            <th></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection