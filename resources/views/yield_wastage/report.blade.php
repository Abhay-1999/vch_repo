@extends('auth.layouts.app')

@section('content')


    <style>

        body{
            background:#f5f5f5;
        }

        .card{
            border:none;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }

        .table th{
            font-size:13px;
            white-space:nowrap;
        }

        .table td{
            font-size:13px;
            vertical-align:middle;
        }

        .report-header{
            background:#0d6efd;
            color:#fff;
            padding:15px;
            border-radius:8px 8px 0 0;
        }

        .summary-box{
            background:#f8f9fa;
            padding:10px;
            border-radius:6px;
            border:1px solid #ddd;
        }

        .table thead{
            background:#212529;
            color:#fff;
        }

        .badge-success{
            background:green;
        }

        .badge-danger{
            background:red;
        }

    </style>


<div class="container-fluid mt-4 mb-5">

    <div class="card">

        <div class="report-header">
            <div class="row">

                <div class="col-md-6">
                    <h3 class="mb-0">
                        Yield & Wastage Report
                    </h3>
                </div>

                <div class="col-md-6 text-end">
                    <h6 class="mb-0">
                        Generated On :
                        {{ date('d-m-Y h:i A') }}
                    </h6>
                </div>

            </div>
        </div>

        <div class="card-body">

            @foreach($data as $row)

            <div class="summary-box mb-4">

                <div class="row">

                    <div class="col-md-3 mb-2">
                        <strong>Test No :</strong><br>
                        {{ $row->test_no }}
                    </div>

                    <div class="col-md-3 mb-2">
                        <strong>Test Date :</strong><br>
                        {{ date('d-m-Y',strtotime($row->test_date)) }}
                    </div>

                    <div class="col-md-3 mb-2">
                        <strong>Tested By :</strong><br>
                        {{ $row->tested_by }}
                    </div>

                    <div class="col-md-3 mb-2">
                        <strong>Remarks :</strong><br>
                        {{ $row->remarks }}
                    </div>

                </div>

            </div>

            <div class="table-responsive mb-5">

                <table class="table table-bordered table-hover align-middle">

                    <thead>

                        <tr>

                            <th>S.No</th>
                            <th>Ingredient</th>
                            <th>AP Weight</th>
                            <th>Trim Loss</th>
                            <th>Cooking Loss</th>
                            <th>Total Loss</th>
                            <th>EP Weight</th>
                            <th>Yield %</th>
                            <th>AP Cost</th>
                            <th>AP Cost/GM</th>
                            <th>EP Cost/GM</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $totalApWeight = 0;
                            $totalEpWeight = 0;
                            $totalLoss = 0;
                        @endphp

                        @foreach($row->details as $key => $dt)

                        @php

                            $loss =
                                $dt->trim_loss +
                                $dt->cooking_loss;

                            $totalApWeight += $dt->ap_weight;
                            $totalEpWeight += $dt->ep_weight;
                            $totalLoss += $loss;

                        @endphp

                        <tr>

                            <td>{{ $key + 1 }}</td>

                            <td>
                                <strong>
                                    {{ $dt->ingredient_name }}
                                </strong>
                            </td>

                            <td>
                                {{ number_format($dt->ap_weight,2) }}
                            </td>

                            <td>
                                {{ number_format($dt->trim_loss,2) }}
                            </td>

                            <td>
                                {{ number_format($dt->cooking_loss,2) }}
                            </td>

                            <td>
                                {{ number_format($loss,2) }}
                            </td>

                            <td>
                                {{ number_format($dt->ep_weight,2) }}
                            </td>

                            <td>

                                @if($dt->yield_percent >= 80)

                                    <span class="badge bg-success">
                                        {{ number_format($dt->yield_percent,2) }}%
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        {{ number_format($dt->yield_percent,2) }}%
                                    </span>

                                @endif

                            </td>

                            <td>
                                ₹ {{ number_format($dt->ap_cost,2) }}
                            </td>

                            <td>
                                ₹ {{ number_format($dt->ap_cost_per_gm,4) }}
                            </td>

                            <td>
                                ₹ {{ number_format($dt->ep_cost_per_gm,4) }}
                            </td>

                            <td>

                                @if($dt->yield_percent >= 80)

                                    <span class="badge bg-success">
                                        Good
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        High Wastage
                                    </span>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot class="table-secondary">

                        <tr>

                            <th colspan="2">
                                Total
                            </th>

                            <th>
                                {{ number_format($totalApWeight,2) }}
                            </th>

                            <th colspan="2"></th>

                            <th>
                                {{ number_format($totalLoss,2) }}
                            </th>

                            <th>
                                {{ number_format($totalEpWeight,2) }}
                            </th>

                            <th colspan="5"></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            @endforeach

        </div>

    </div>

</div>

@endsection