@extends('auth.layouts.app')

@section('content')

<style>

    .report-table th{
        background:#1f3b73;
        color:#fff;
        text-align:center;
        vertical-align:middle;
        font-size:14px;
        white-space:nowrap;
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

                            <th>
                                Units Sold <br>
                                (period)
                            </th>

                            <th>
                                Plate Cost (Rs)
                            </th>

                            <th>
                                Selling Price ex GST (Rs)
                            </th>

                            <th>
                                Contribution Margin (Rs)
                            </th>

                            <th>
                                Total Sales (Rs)
                            </th>

                            <th>
                                Total Cost (Rs)
                            </th>

                            <th>
                                Total Margin (Rs)
                            </th>

                            <th>
                                Sales Mix %
                            </th>

                            <th>
                                Margin Mix %
                            </th>

                            <th>
                                Popularity
                            </th>

                            <th>
                                Profitability
                            </th>

                            <th>
                                Classification
                            </th>

                            <th>
                                Action
                            </th>

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

                            <!-- Item Code -->
                            <td>
                                {{ $row['item_code'] }}
                            </td>

                            <!-- Item Name -->
                            <td>
                                {{ $row['item_name'] }}
                            </td>

                            <!-- Units Sold -->
                            <td class="text-end">
                                {{ $row['units_sold'] }}
                            </td>

                            <!-- Plate Cost -->
                            <td class="text-end">
                                {{ number_format($row['plate_cost'],0) }}
                            </td>

                            <!-- Selling Price -->
                            <td class="text-end">
                                {{ number_format($row['selling_price'],0) }}
                            </td>

                            <!-- Contribution Margin -->
                            <td class="text-end">
                                {{ number_format($row['contribution_margin'],0) }}
                            </td>

                            <!-- Total Sales -->
                            <td class="text-end">
                                {{ number_format($row['total_sales'],0) }}
                            </td>

                            <!-- Total Cost -->
                            <td class="text-end">
                                {{ number_format($row['total_cost'],0) }}
                            </td>

                            <!-- Total Margin -->
                            <td class="text-end">
                                {{ number_format($row['total_margin'],0) }}
                            </td>

                            <!-- Sales Mix -->
                            <td class="text-end">
                                {{ $row['sales_mix'] }}%
                            </td>

                            <!-- Margin Mix -->
                            <td class="text-end">
                                {{ $row['margin_mix'] }}%
                            </td>

                            <!-- Popularity -->
                            <td>
                                {{ $row['popularity'] }}
                            </td>

                            <!-- Profitability -->
                            <td>
                                {{ $row['profitability'] }}
                            </td>

                            <!-- Classification -->
                            <td
                                class="
                                @if($row['classification'] == 'STAR')
                                    star
                                @elseif($row['classification'] == 'PUZZLE')
                                    puzzle
                                @elseif($row['classification'] == 'PLOWHORSE')
                                    plowhorse
                                @else
                                    dog
                                @endif
                                "
                            >
                                {{ $row['classification'] }}
                            </td>

                            <!-- Action -->
                            <td>
                                {{ $row['action'] }}
                            </td>

                        </tr>

                        @endforeach

                        <!-- TOTAL ROW -->

                        <tr style="font-weight:700; background:#f2f2f2;">

                            <td></td>

                            <td class="text-center">
                                TOTAL
                            </td>

                            <td class="text-end">
                                {{ $totalUnitsSold }}
                            </td>

                            <td></td>
                            <td></td>
                            <td></td>

                            <td class="text-end">
                                {{ number_format($grandSales,0) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($grandCost,0) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($grandMargin,0) }}
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

        </div>

    </div>

</div>

@endsection