@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h4 class="mb-0">
                Discount Usage By Code Report
            </h4>

        </div>

        <div class="card-body">

            {{-- Filter Form --}}
            <form
                method="GET"
                action="{{ route('reports.discount-usage') }}"
                class="mb-4"
            >

                <div class="row g-3 align-items-end">

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                            required
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                            required
                        >

                    </div>

                    <div class="col-md-3">

                        <button
                            class="btn btn-primary w-100"
                            type="submit"
                        >
                            Search Report
                        </button>

                    </div>

                    <div class="col-md-3">

                        <a
                            href="{{ route('reports.discount-usage') }}"
                            class="btn btn-secondary w-100"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>


            {{-- Table --}}
            @if(isset($report))

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                >

                    <thead class="table-dark">

                        <tr>

                            <th width="20%">
                                Discount Code
                            </th>

                            <th width="15%" class="text-center">
                                Bills
                            </th>

                            <th width="20%" class="text-end">
                                Total Discount
                            </th>

                            <th width="20%" class="text-end">
                                % Share
                            </th>

                            <th width="15%" class="text-center">
                                Rank
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($report as $r)

                        <tr>

                            <td class="fw-bold text-primary">

                                {{ $r['discount_code'] }}

                            </td>

                            <td class="text-center">

                                {{ $r['bill_count'] }}

                            </td>

                            <td class="text-end text-success fw-bold">

                                ₹ {{ $r['discount_amount'] }}

                            </td>

                            <td class="text-end">

                                {{ $r['percent'] }}

                            </td>

                            <td class="text-center">

                                <span class="badge bg-info">

                                    {{ $r['rank'] }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-4"
                            >

                                No record found

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            @endif

        </div>

    </div>

</div>

@endsection