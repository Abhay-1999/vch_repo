@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">


    {{-- REPORT CARD --}}
    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                Item-wise Sales Report

            </h5>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                Net Revenue = Gross - Discount |
                Margin = Net Revenue - Recipe Cost |
                Rank by Quantity Sold

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>Rank</th>

                            <th>Item Code</th>

                            <th>Item Name</th>

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
                                Recipe Cost
                            </th>

                            <th class="text-end">
                                Gross Margin
                            </th>

                            <th class="text-end">
                                Margin %
                            </th>

                            <th class="text-end">
                                % of Total Sales
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($items as $item)

                        <tr>

                            <td>
                                {{ $item->rank }}
                            </td>

                            <td>
                                {{ $item->item_code }}
                            </td>

                            <td>
                                {{ $item->item_name }}
                            </td>

                            <td>
                                {{ $item->category }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->qty) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->gross, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->discount, 2) }}
                            </td>

                            <td class="text-end fw-bold text-primary">
                                {{ number_format($item->net_revenue, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->recipe_cost, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->gross_margin, 2) }}
                            </td>

                            <td class="text-end">

                                {{ $item->margin_percent !== null
                                    ? number_format($item->margin_percent, 1) . '%'
                                    : '-' }}

                            </td>

                            <td class="text-end">

                                {{ number_format($item->sales_percent, 1) }}%

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="12"
                                class="text-center text-danger">

                                No records found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                    {{-- TOTAL --}}
                    <tfoot class="table-warning">

                        <tr>

                            <th colspan="4">

                                TOTAL

                            </th>

                            <th class="text-end">

                                {{ number_format($totalQty) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($totalGross, 2) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($totalDiscount, 2) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($totalNet, 2) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($totalRecipeCost, 2) }}

                            </th>

                            <th class="text-end">

                                {{ number_format($totalMargin, 2) }}

                            </th>

                            <th class="text-end">

                                {{ $totalNet > 0
                                    ? number_format(($totalMargin / $totalNet) * 100, 1) . '%'
                                    : '-' }}

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