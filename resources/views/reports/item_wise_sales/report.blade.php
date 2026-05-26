@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                {{ $report->report_name }}
                ({{ $report->report_code }})

            </h4>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                Net Revenue = Gross - Discount;
                Margin = Net - Recipe Cost;
                ranking by quantity

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

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

                            <th class="text-end">
                                Rank
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($items as $item)

                        <tr>

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

                            <td class="text-end fw-bold text-success">
                                {{ number_format($item->net_revenue, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->recipe_cost, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->gross_margin, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->margin_percent, 1) }}%
                            </td>

                            <td class="text-end">
                                {{ number_format($item->sales_percent, 1) }}%
                            </td>

                            <td class="text-end">
                                {{ $item->rank }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot class="table-warning">

                        <tr>

                            <th colspan="3">
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
                                    ? number_format(($totalMargin / $totalNet) * 100, 1)
                                    : 0 }}%

                            </th>

                            <th></th>

                            <th></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection