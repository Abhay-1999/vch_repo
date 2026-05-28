
@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Day Book / Cash Register Report
            </h5>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">

                Back

            </a>

        </div>

        {{-- BODY --}}
        <div class="card-body">

            <div class="mb-3">

                <strong>
                    Business Date :
                </strong>

                {{ $businessDate }}

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Particular
                            </th>

                            <th class="text-end">
                                Amount (₹)
                            </th>

                            <th>
                                Remarks
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                Opening Balance
                            </td>

                            <td class="text-end">
                                {{ number_format($openingBalance, 2) }}
                            </td>

                            <td>
                                Cash in drawer at open
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Cash Sales
                            </td>

                            <td class="text-end">
                                {{ number_format($cashSales, 2) }}
                            </td>

                            <td>
                                Total cash sales
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Other Receipts
                            </td>

                            <td class="text-end">
                                {{ number_format($otherReceipts, 2) }}
                            </td>

                            <td>
                                Customer deposits etc.
                            </td>

                        </tr>

                        <tr class="table-success">

                            <td>
                                <strong>Total Inflow</strong>
                            </td>

                            <td class="text-end">
                                <strong>
                                    {{ number_format($totalInflow, 2) }}
                                </strong>
                            </td>

                            <td>
                                Open + Sales + Receipts
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Expenses
                            </td>

                            <td class="text-end">
                                {{ number_format($expenses, 2) }}
                            </td>

                            <td>
                                Petty cash expenses
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Bank Deposit
                            </td>

                            <td class="text-end">
                                {{ number_format($bankDeposit, 2) }}
                            </td>

                            <td>
                                End-of-day banking
                            </td>

                        </tr>

                        <tr class="table-primary">

                            <td>
                                <strong>Closing Balance</strong>
                            </td>

                            <td class="text-end">
                                <strong>
                                    {{ number_format($closingBalance, 2) }}
                                </strong>
                            </td>

                            <td>
                                Inflow - Expenses - Deposit
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Drawer Count
                            </td>

                            <td class="text-end">
                                {{ number_format($drawerCount, 2) }}
                            </td>

                            <td>
                                Physical drawer count
                            </td>

                        </tr>

                        <tr class="{{ abs($difference) > 100 ? 'table-danger' : 'table-warning' }}">

                            <td>
                                <strong>Difference</strong>
                            </td>

                            <td class="text-end">
                                <strong>
                                    {{ number_format($difference, 2) }}
                                </strong>
                            </td>

                            <td>

                                @if(abs($difference) > 100)

                                    Difference exceeds limit

                                @else

                                    Within acceptable range

                                @endif

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

