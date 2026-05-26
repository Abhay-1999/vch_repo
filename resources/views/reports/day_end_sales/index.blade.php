@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">
                Reports Filter
            </h4>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('reports.day-end-sales.generate') }}">

                @csrf

                <div class="row">

                    {{-- REPORT SELECT --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Select Report
                        </label>

                        <select name="report_code"
                                class="form-control"
                                required>

                            <option value="">
                                Select Report
                            </option>

                            @foreach($reports as $report)

                                <option value="{{ $report->report_code }}">

                                    {{ $report->report_code }}
                                    -
                                    {{ $report->report_name }}

                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- BUSINESS DATE --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Business Date
                        </label>

                        <input type="date"
                               name="business_date"
                               class="form-control"
                               value="{{ date('Y-m-d') }}"
                               required>

                    </div>

                    {{-- BUTTON --}}
                    <div class="col-md-2 mt-4">

                        <button class="btn btn-primary">

                            Generate Report

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection