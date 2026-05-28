@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Current Stock Report
            </h5>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

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
                                UOM
                            </th>

                            <th class="text-end">
                                Opening Qty
                            </th>

                            <th class="text-end">
                                Inward Qty
                            </th>

                            <th class="text-end">
                                Outward Qty
                            </th>

                            <th class="text-end">
                                Adjustment Qty
                            </th>

                            <th class="text-end">
                                Current Qty
                            </th>

                            <th class="text-end">
                                Reorder Level
                            </th>

                            <th>
                                Status
                            </th>
                            <th class="text-end">
    Value (Rs)
</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($stocks as $row)

                        <tr>

                            <td>
                                {{ $row->material_code }}
                            </td>

                            <td>
                                {{ $row->material_name }}
                            </td>

                            <td>
                                {{ $row->category }}
                            </td>

                            <td>
                                {{ $row->base_uom }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row->opening_qty, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row->inward_qty, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row->outward_qty, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row->adjustment_qty, 2) }}
                            </td>

                            <td class="text-end fw-bold">

                                {{ number_format($row->current_qty, 2) }}

                            </td>

                            <td class="text-end">

                                {{ number_format($row->reorder_level, 2) }}

                            </td>

                            <td>

                                @if($row->status == 'OK')

                                    <span class="badge bg-success">
                                        OK
                                    </span>
<td class="text-end fw-bold text-primary">

    {{ number_format($row->stock_value, 2) }}

</td>
                                @elseif($row->status == 'REORDER')

                                    <span class="badge bg-warning">
                                        REORDER
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        OUT OF STOCK
                                    </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="11"
                                class="text-center text-danger">

                                No records found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>
<tfoot class="table-warning">

    <tr>

        <th colspan="8">

            TOTAL

        </th>

        <th class="text-end">

            {{ number_format($totalCurrentStock, 2) }}

        </th>

        <th></th>

        <th></th>

        <th class="text-end">

            {{ number_format($totalStockValue, 2) }}

        </th>

    </tr>

</tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection