@extends('auth.layouts.app')

@section('content')

<style>

    .report-table th{
        background:#1f3b73;
        color:#fff;
        text-align:center;
        vertical-align:middle;
        font-size:14px;
    }

    .report-table td{
        font-size:13px;
        vertical-align:middle;
    }

    .star{
        background:#f3d46b;
        font-weight:700;
    }

    .puzzle{
        background:#d9c7ff;
        font-weight:700;
        color:#6f42c1;
    }

    .plowhorse{
        background:#cfe2ff;
        font-weight:700;
        color:#0d47a1;
    }

    .dog{
        background:#f8c8c8;
        font-weight:700;
        color:#c00000;
    }

    .legend-title{
        background:#c99700;
        color:#fff;
        font-weight:700;
    }

</style>

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header text-white"
             style="background:#1f3b73;">

            <h4 class="mb-0">
                Menu Engineering Report
            </h4>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered report-table">

                    <thead>

                        <tr>

                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Units Sold<br>(period)</th>
                            <th>Plate Cost (Rs)</th>
                            <th>Selling Price ex GST (Rs)</th>
                            <th>Contribution Margin (Rs)</th>
                            <th>Total Sales (Rs)</th>
                            <th>Total Cost (Rs)</th>
                            <th>Total Margin (Rs)</th>
                            <th>Sales Mix %</th>
                            <th>Margin Mix %</th>
                            <th>Popularity</th>
                            <th>Profitability</th>
                            <th>Classification</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $grandSales = 0;
                            $grandCost = 0;
                            $grandMargin = 0;
                        @endphp

                        @foreach($report as $row)

                        @php
                            $grandSales += $row['total_sales'];
                            $grandCost += $row['total_cost'];
                            $grandMargin += $row['total_margin'];
                        @endphp

                        <tr>

                            <td>{{ $row['item_code'] }}</td>

                            <td>{{ $row['item_name'] }}</td>

                            <td class="text-end">
                                {{ $row['units_sold'] }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row['plate_cost'],2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row['selling_price'],2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row['contribution_margin'],2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row['total_sales'],2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row['total_cost'],2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($row['total_margin'],2) }}
                            </td>

                            <td class="text-end">
                                {{ $row['sales_mix'] }}%
                            </td>

                            <td class="text-end">
                                {{ $row['margin_mix'] }}%
                            </td>

                            <td>
                                {{ $row['popularity'] }}
                            </td>

                            <td>
                                {{ $row['profitability'] }}
                            </td>

                            <td
                                class="
                                @if($row['classification']=='STAR')
                                    star
                                @elseif($row['classification']=='PUZZLE')
                                    puzzle
                                @elseif($row['classification']=='PLOWHORSE')
                                    plowhorse
                                @else
                                    dog
                                @endif
                                "
                            >
                                {{ $row['classification'] }}
                            </td>

                            <td>
                                {{ $row['action'] }}
                            </td>

                        </tr>

                        @endforeach

                        <tr style="font-weight:700;">

                            <td></td>

                            <td>TOTAL</td>

                            <td class="text-end">
                                {{ $totalUnitsSold }}
                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                            <td class="text-end">
                                {{ number_format($grandSales,2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($grandCost,2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($grandMargin,2) }}
                            </td>

                            <td class="text-end">
                                100.0%
                            </td>

                            <td class="text-end">
                                100.0%
                            </td>

                            <td colspan="4"></td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <br>


        </div>

    </div>

</div>

@endsection