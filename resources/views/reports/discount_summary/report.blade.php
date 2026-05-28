
@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Discount Summary Report
            </h5>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        {{-- BODY --}}
        <div class="card-body">

            {{-- FILTER INFO --}}
            <div class="row mb-3">

                <div class="col-md-12">

                    <strong>
                        From Date:
                    </strong>

                    {{ $fromDate }}

                    <span class="mx-3">|</span>

                    <strong>
                        To Date:
                    </strong>

                    {{ $toDate }}

                </div>

            </div>

            {{-- GRAND TOTALS --}}
            @php

                $totalBills =
                    $discounts->sum('total_bills');

                $totalGrossSales =
                    $discounts->sum('gross_sales');

            @endphp

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Date
                            </th>

                            <th>
                                Discount Type
                            </th>

                            <th>
                                Discount Name
                            </th>

                            <th>
                                Discount Code
                            </th>

                            <th class="text-end">
                                # Bills
                            </th>

                            <th class="text-end">
                                Discount Given (Rs)
                            </th>

                            <th class="text-end">
                                Gross Sales (Rs)
                            </th>

                            <th class="text-end">
                                Discount % of Sales
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($discounts as $row)

                            <tr>

                                {{-- DATE --}}
                                <td>

                                    {{ $row->report_date }}

                                </td>

                                {{-- DISCOUNT TYPE --}}
                                <td>

                                    {{ $row->discount_type ?? '-' }}

                                </td>

                                {{-- DISCOUNT NAME --}}
                                <td>

                                    {{ $row->discount_name ?? '-' }}

                                </td>

                                {{-- DISCOUNT CODE --}}
                                <td>

                                    {{ $row->discount_code ?? '-' }}

                                </td>

                                {{-- TOTAL BILLS --}}
                                <td class="text-end">

                                    {{ number_format($row->total_bills) }}

                                </td>

                                {{-- TOTAL DISCOUNT --}}
                                <td class="text-end">

                                    ₹ {{ number_format($row->total_discount, 2) }}

                                </td>

                                {{-- GROSS SALES --}}
                                <td class="text-end">

                                    ₹ {{ number_format($row->gross_sales, 2) }}

                                </td>

                                {{-- DISCOUNT % --}}
                                <td class="text-end">

                                    {{ number_format($row->discount_percent, 2) }}%

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

                    {{-- FOOTER --}}
                    <tfoot class="table-warning">

                        <tr>

                            <th colspan="4">

                                GRAND TOTAL

                            </th>

                            <th class="text-end">

                                {{ number_format($totalBills) }}

                            </th>

                            <th class="text-end">

                                ₹ {{ number_format($grandTotalDiscount, 2) }}

                            </th>

                            <th class="text-end">

                                ₹ {{ number_format($totalGrossSales, 2) }}

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

