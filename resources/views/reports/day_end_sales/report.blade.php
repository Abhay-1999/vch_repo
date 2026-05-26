@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                SAMPLE — Day-End Sales Summary (RPT-001)
            </h4>

            <a href="{{ route('reports.day-end-sales') }}"
               class="btn btn-light btn-sm">
                Back
            </a>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                <strong>
                    Live formulas:
                </strong>

                Net Sales = Taxable + CGST + SGST + Round Off;
                payment modes must sum to Net

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>Date</th>

                            <th class="text-end">
                                Total Orders
                            </th>

                            <th class="text-end">
                                Gross Sales (Rs)
                            </th>

                            <th class="text-end">
                                Discount (Rs)
                            </th>

                            <th class="text-end">
                                Taxable (Rs)
                            </th>

                            <th class="text-end">
                                CGST (Rs)
                            </th>

                            <th class="text-end">
                                SGST (Rs)
                            </th>

                            <th class="text-end">
                                Net Sales (Rs)
                            </th>

                            <th class="text-end">
                                Round Off (Rs)
                            </th>

                            <th class="text-end">
                                Cash (Rs)
                            </th>

                            <th class="text-end">
                                Card (Rs)
                            </th>

                            <th class="text-end">
                                UPI (Rs)
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                {{ $date }}
                            </td>

                            <td class="text-end">
                                {{ number_format($totalOrders) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($grossSales, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($discount, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($taxable, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($cgst, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($sgst, 2) }}
                            </td>

                            <td class="text-end fw-bold text-success">
                                {{ number_format($netSales, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($roundOff, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($cash, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($card, 2) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($upi, 2) }}
                            </td>

                        </tr>

                    </tbody>

                    <tfoot class="table-warning">

                        <tr>

                            <th>
                                TOTAL
                            </th>

                            <th class="text-end">
                                {{ number_format($totalOrders) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($grossSales, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($discount, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($taxable, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($cgst, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($sgst, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($netSales, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($roundOff, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($cash, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($card, 2) }}
                            </th>

                            <th class="text-end">
                                {{ number_format($upi, 2) }}
                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            @php

                $paymentRecon =
                    $cash +
                    $card +
                    $upi;

            @endphp

            <div class="row mt-4">

                <div class="col-md-6 offset-md-6">

                    <table class="table table-bordered">

                        <tr class="table-info">

                            <th width="60%">
                                Payment Recon Check
                            </th>

                            <td class="text-end fw-bold">

                                {{ number_format($paymentRecon, 2) }}

                            </td>

                        </tr>

                        <tr class="{{ $difference == 0 ? 'table-success' : 'table-danger' }}">

                            <th>
                                Diff (Net Sales - Pmt Modes)
                            </th>

                            <td class="text-end fw-bold">

                                {{ number_format($difference, 2) }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection