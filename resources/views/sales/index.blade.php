@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-3">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Daily Food Cost Report</h4>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped table-hover">

                <thead class="table-primary text-center">

                    <tr>

                        <th>Date</th>

                        <th>Item Code</th>

                        <th>Item Name</th>

                        <th>Qty Sold</th>

                        <th>Selling Price ex GST (Rs)</th>

                        <th>Plate Cost (Rs)</th>

                        <th>Sales Value (Rs)</th>

                        <th>COGS (Rs)</th>

                        <th>Gross Margin (Rs)</th>

                        <th>Food Cost %</th>

                        <th>Margin %</th>

                        <th>Notes</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($sales as $sale)

                    <tr>

                        <td>
                            {{ $sale->sale_date }}
                        </td>

                        <td>
                            {{ $sale->item_code }}
                        </td>

                        <td>
                            {{ $sale->item_name }}
                        </td>

                        <td class="text-end">
                            {{ $sale->qty_sold }}
                        </td>

                        <td class="text-end">
                            ₹ {{ number_format($sale->selling_price, 2) }}
                        </td>

                        <td class="text-end">
                            ₹ {{ number_format($sale->plate_cost, 2) }}
                        </td>

                        <td class="text-end">
                            ₹ {{ number_format($sale->sales_value, 2) }}
                        </td>

                        <td class="text-end">
                            ₹ {{ number_format($sale->cogs, 2) }}
                        </td>

                        <td class="text-end">
                            ₹ {{ number_format($sale->gross_margin, 2) }}
                        </td>

                        <td class="text-end">
                            {{ number_format($sale->food_cost_percent, 2) }}%
                        </td>

                        <td class="text-end">
                            {{ number_format($sale->margin_percent, 2) }}%
                        </td>

                        <td>
                            {{ $sale->notes }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="12" class="text-center text-danger">
                            No Sales Data Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection