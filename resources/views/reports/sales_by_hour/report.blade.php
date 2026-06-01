@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    <div class="card shadow border-0">

        <div class="card-header bg-dark text-white">

            <h4 class="mb-0">

                Sales By Hour

            </h4>

            <small>

                {{ $fromDate }}
                to
                {{ $toDate }}

            </small>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="bg-dark text-white">

                        <tr>

                            <th>#</th>

                            <th>
                                Hour
                            </th>

                            <th class="text-end">
                                Orders
                            </th>

                            <th class="text-end">
                                Sales
                            </th>

                            <th class="text-end">
                                Avg Order
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($reportData as $key => $row)

                        <tr>

                            <td>

                                {{ $key + 1 }}

                            </td>

                            <td>

                                {{ $row->sale_hour }}

                            </td>

                            <td class="text-end">

                                {{ $row->total_orders }}

                            </td>

                            <td class="text-end text-success">

                                ₹{{ number_format($row->total_sales,2) }}

                            </td>

                            <td class="text-end text-primary">

                                ₹{{ number_format($row->avg_order,2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center">

                                No data found

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