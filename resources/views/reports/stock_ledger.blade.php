{{-- resources/views/reports/stock_ledger.blade.php --}}

@extends('auth.layouts.app')

@section('content')

<style>

    .table-responsive-custom{
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-custom table{
        min-width: 2600px;
        white-space: nowrap;
    }

</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">

        <h4>
            Stock Ledger Report
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

                    <th>Txn ID</th>
                    <th>Txn Date</th>
                    <th>Txn Time</th>

                    <th>Material Code</th>
                    <th>Material Name</th>

                    <th>Base UoM</th>

                    <th>Txn Type</th>

                    <th>Reference Type</th>
                    <th>Reference No.</th>

                    <th>Supplier ID</th>

                    <th>Item Code (POS)</th>

                    <th>Inward Qty</th>
                    <th>Outward Qty</th>
                    <th>Adjustment Qty</th>

                    <th>Rate (₹/Base UoM)</th>

                    <th>Value (₹)</th>

                    <th>Running Balance (Base UoM)</th>

                    <th>Reason / Notes</th>

                    <th>Performed By</th>
                    <th>Approved By</th>

                    <th>Posted On</th>

                </tr>

            </thead>

            <tbody>

                @php
                    $runningBalance = 0;
                @endphp

                @forelse($stocks as $key => $stock)

                    @php

                        $inwardQty = $stock->accepted_qty_base_uom ?? 0;

                        $outwardQty = 0;

                        $adjustmentQty = 0;

                        $runningBalance += $inwardQty;

                        $value = $inwardQty * $stock->effective_cost_per_base_uom;

                    @endphp

                    <tr>


                        <td>{{ $stock->id }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($stock->created_at)->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($stock->created_at)->format('h:i A') }}
                        </td>

                        <td>{{ $stock->material_code }}</td>

                        <td>{{ $stock->material_name }}</td>

                        <td>{{ $stock->base_uom }}</td>

                        <td>
                            GRN INWARD
                        </td>

                        <td>
                            GRN
                        </td>

                        <td>
                            {{ $stock->grn_no }}
                        </td>

                        <td>
                            {{ $stock->header->supplier_id ?? '' }}
                        </td>

                        <td>
                            --
                        </td>

                        <td>
                            {{ $inwardQty }}
                        </td>

                        <td>
                            {{ $outwardQty }}
                        </td>

                        <td>
                            {{ $adjustmentQty }}
                        </td>

                        <td>
                            ₹ {{ number_format($stock->effective_cost_per_base_uom, 2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($value, 2) }}
                        </td>

                        <td>
                            {{ number_format($runningBalance, 2) }}
                        </td>

                        <td>
                            {{ $stock->remark }}
                        </td>

                        <td>
                            {{ $stock->header->received_by ?? '' }}
                        </td>

                        <td>
                            {{ $stock->header->verified_by ?? '' }}
                        </td>

                        <td>
                            {{ $stock->created_at }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="22" class="text-center text-danger">

                            No Ledger Records Found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection