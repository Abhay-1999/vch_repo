@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                {{ $report->report_name }}
                ({{ $report->report_code }})

            </h4>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                <strong>
                    Formula:
                </strong>

                Closing = Opening + CashSales + Receipts - Exp - Deposit;
                Diff = Physical - System
                (flag if > Rs 100)

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>Date</th>

                            <th class="text-end">
                                Opening Balance
                            </th>

                            <th class="text-end">
                                Cash Sales
                            </th>

                            <th class="text-end">
                                Other Receipts
                            </th>

                            <th class="text-end">
                                Total Inflow
                            </th>

                            <th class="text-end">
                                Expenses
                            </th>

                            <th class="text-end">
                                Bank Deposit
                            </th>

                            <th class="text-end">
                                Closing Balance
                            </th>

                            <th class="text-end">
                                Drawer Count
                            </th>

                            <th class="text-end">
                                Difference
                            </th>

                        </tr>

                    </thead>

                  <tbody>

    @foreach($records as $row)

    <tr>

        <td>
            {{ $row->date }}
        </td>

        <td class="text-end">
            {{ number_format($row->opening, 2) }}
        </td>

        <td class="text-end">
            {{ number_format($row->cash_sales, 2) }}
        </td>

        <td class="text-end">
            {{ number_format($row->other_receipts, 2) }}
        </td>

        <td class="text-end">
            {{ number_format($row->total_inflow, 2) }}
        </td>

        <td class="text-end">
            {{ number_format($row->expenses, 2) }}
        </td>

        <td class="text-end">
            {{ number_format($row->bank_deposit, 2) }}
        </td>

        <td class="text-end fw-bold text-primary">
            {{ number_format($row->closing_balance, 2) }}
        </td>

        <td class="text-end">
            {{ number_format($row->drawer_count, 2) }}
        </td>

        <td class="text-end fw-bold
            {{ abs($row->difference) > 100
                ? 'text-danger'
                : 'text-success' }}">

            {{ number_format($row->difference, 2) }}

        </td>

    </tr>

    @endforeach

</tbody>

                    <tfoot class="table-warning">

                        <tr>

                            <th>
                                TOTAL
                            </th>

                            <th></th>

                            <th class="text-end">
                                {{ number_format($totalCashSales, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($totalReceipts, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($totalInflow, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($totalExpenses, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($totalDeposits, 2) }}
                            </th>

                            <th></th>

                            <th></th>

                            <th class="text-end fw-bold">

                                {{ number_format($totalDifference, 2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection