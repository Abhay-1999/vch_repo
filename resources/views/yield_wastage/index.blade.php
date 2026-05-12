@extends('auth.layouts.app')

@section('content')

<style>

    .table-card{
        border-radius:10px;
        overflow:hidden;
    }

    .table-title{
        font-size:24px;
        font-weight:700;
    }

    .custom-table thead{
        background:#1f3b6d;
        color:#fff;
    }

    .custom-table th{
        font-size:14px;
        vertical-align:middle;
        text-align:center;
    }

    .custom-table td{
        font-size:13px;
        vertical-align:middle;
    }

    .yield-good{
        background:#9ccc65 !important;
        font-weight:600;
    }

    .yield-medium{
        background:#ffd54f !important;
        font-weight:600;
    }

    .yield-low{
        background:#ffb74d !important;
        font-weight:600;
    }

</style>

<div class="container mt-4">

    <div class="card shadow table-card">

        {{-- Header --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0 table-title">
                Yield & Wastage Testing
            </h4>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped custom-table">

                    <thead>

                        <tr>

                            <th>Date</th>

                            <th>Ingredient Code</th>

                            <th>Ingredient Name</th>

                            <th width="260">
                                Yield / Wastage Details
                            </th>

                            <th>Yield %</th>

                            <th>AP Cost</th>

                            <th width="240">
                                Cost Analysis
                            </th>

                            <th>Tested By</th>

                            <th>Approved By</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($reports as $row)

                        <tr>

                            <td>
                                {{ $row->last_updated
                                    ? \Carbon\Carbon::parse($row->last_updated)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $row->ingredient_code }}
                            </td>

                            <td>
                                {{ $row->ingredient_name }}
                            </td>

                            <td>

                                <b>AP:</b>
                                {{ number_format($row->ap_weight,0) }} gm

                                <br>

                                <b>Trim:</b>
                                {{ number_format($row->trim_loss,0) }} gm

                                <br>

                                <b>Cooking:</b>
                                {{ number_format($row->cooking_loss,0) }} gm

                                <br>

                                <b>EP:</b>
                                {{ number_format($row->ep_weight,0) }} gm

                            </td>

                            <td class="text-center

                                @if($row->yield_percent >= 85)
                                    yield-good
                                @elseif($row->yield_percent >= 75)
                                    yield-medium
                                @else
                                    yield-low
                                @endif
                            ">

                                {{ number_format($row->yield_percent,2) }}%

                            </td>

                            <td class="text-end">
                                ₹ {{ number_format($row->ap_cost,2) }}
                            </td>

                            <td>

                                <b>EP:</b>
                                {{ number_format($row->ep_cost_per_gm,3) }}

                                <br>

                                <b>AP:</b>
                                {{ number_format($row->ap_cost_per_gm,3) }}

                                <br>

                                <b>Factor:</b>
                                {{ number_format($row->cost_increase_factor,3) }}

                            </td>

                            <td>
                                Chef A
                            </td>

                            <td>
                                Head Chef
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="9" class="text-center text-danger">

                                No Data Found

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