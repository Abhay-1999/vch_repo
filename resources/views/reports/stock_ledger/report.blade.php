@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-0">
                    Stock Ledger Report
                </h5>

                @if(!empty($fromDate) && !empty($toDate))

                    <small>
                        From:
                        {{ $fromDate }}
                        |
                        To:
                        {{ $toDate }}
                    </small>

                @endif

            </div>

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

                    @if(!empty($materialCode))

                        <div class="alert alert-info mb-0">

                            <strong>
                                Selected Material:
                            </strong>

                            {{ $materialCode }}

                        </div>

                    @endif

                </div>

            </div>

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Last Updated
                            </th>

                            <th>
                                Material Code
                            </th>

                            <th>
                                Material Name
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Transaction Type
                            </th>

                            <th>
                                Reference No
                            </th>

                            <th class="text-end">
                                Qty
                            </th>

                            <th>
                                UOM
                            </th>

                            <th class="text-end">
                                Min Stock
                            </th>

                            <th class="text-end">
                                Purchase Cost
                            </th>

                            <th>
                                Supplier
                            </th>

                            <th class="text-end">
                                Balance Qty
                            </th>

                            <th>
                                Remarks
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $grandTotalQty = 0;
                        @endphp

                        @forelse($ledger as $row)

                            @php
                                $grandTotalQty += $row->qty;
                            @endphp

                            <tr>

                                {{-- DATE --}}
                                <td>

                                    {{ $row->last_updated
                                        ? date('d-m-Y', strtotime($row->last_updated))
                                        : '-' }}

                                </td>

                                {{-- MATERIAL CODE --}}
                                <td>

                                    {{ $row->material_code }}

                                </td>

                                {{-- MATERIAL NAME --}}
                                <td>

                                    {{ $row->material_name }}

                                </td>

                                {{-- CATEGORY --}}
                                <td>

                                    {{ $row->category }}

                                </td>

                                {{-- TRANSACTION TYPE --}}
                                <td>

                                    <span class="badge bg-primary">

                                        {{ $row->transaction_type }}

                                    </span>

                                </td>

                                {{-- REFERENCE --}}
                                <td>

                                    {{ $row->reference_no }}

                                </td>

                                {{-- QTY --}}
                                <td class="text-end fw-bold">

                                    {{ number_format($row->qty, 2) }}

                                </td>

                                {{-- UOM --}}
                                <td>

                                    {{ $row->base_uom }}

                                </td>

                                {{-- MIN STOCK --}}
                                <td class="text-end">

                                    {{ number_format($row->min_stock, 2) }}

                                </td>

                                {{-- PURCHASE COST --}}
                                <td class="text-end">

                                    {{ number_format($row->purchase_cost, 2) }}

                                </td>

                                {{-- SUPPLIER --}}
                                <td>

                                    {{ $row->supplier ?? '-' }}

                                </td>

                                {{-- BALANCE --}}
                                <td class="text-end text-success fw-bold">

                                    {{ number_format($row->balance_qty, 2) }}

                                </td>

                                {{-- REMARKS --}}
                                <td>

                                    {{ $row->remarks }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="13"
                                    class="text-center text-danger">

                                    No records found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    {{-- FOOTER --}}
                    <tfoot class="table-warning">

                        <tr>

                            <th colspan="6">

                                TOTAL QTY

                            </th>

                            <th class="text-end">

                                {{ number_format($grandTotalQty, 2) }}

                            </th>

                            <th colspan="6"></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection