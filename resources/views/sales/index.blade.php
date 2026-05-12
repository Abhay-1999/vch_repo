@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">
            <h4>Daily Food Cost Report</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead class="table-primary">

                    <tr>

                        <th>Date</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Sales Value</th>
                        <th>COGS</th>
                        <th>Gross Margin</th>
                        <th>FC %</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($sales as $sale)

                    <tr>

                        <td>
                            {{ $sale->sale_date }}
                        </td>

                        <td>
                            {{ $sale->menuItem->item_name }}
                        </td>

                        <td>
                            {{ $sale->qty_sold }}
                        </td>

                        <td>
                            ₹ {{ number_format($sale->sales_value,2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($sale->cogs,2) }}
                        </td>

                        <td>
                            ₹ {{ number_format($sale->gross_margin,2) }}
                        </td>

                        <td>

                            {{ number_format($sale->food_cost_percent,2) }} %

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection