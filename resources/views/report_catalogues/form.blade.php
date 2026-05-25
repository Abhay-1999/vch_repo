<div class="row">

    {{-- Report Name --}}

    <div class="col-md-4 mb-3">

        <label class="form-label fw-bold">
            Report Name
        </label>

        <input type="text"
               name="report_name"
               class="form-control @error('report_name') is-invalid @enderror"
               value="{{ old('report_name', $report->report_name ?? '') }}">

        @error('report_name')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

    {{-- Category --}}

    <div class="col-md-3 mb-3">

        <label class="form-label fw-bold">
            Category
        </label>

        <select name="category"
                class="form-control @error('category') is-invalid @enderror">

            <option value="">Select Category</option>

            @php

                $categories = [

                    'Sales',
                    'Item',
                    'Inventory',
                    'CRM',
                    'Discount',
                    'Cash',
                    'Tax',
                    'Staff',
                    'Online',
                    'Kitchen',
                    'Audit',
                    'Operational'

                ];

            @endphp

            @foreach($categories as $category)

                <option value="{{ $category }}"
                    {{ old('category', $report->category ?? '') == $category ? 'selected' : '' }}>

                    {{ $category }}

                </option>

            @endforeach

        </select>

        @error('category')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

    {{-- Frequency --}}

    <div class="col-md-3 mb-3">

        <label class="form-label fw-bold">
            Frequency
        </label>

        <select name="frequency"
                class="form-control @error('frequency') is-invalid @enderror">

            <option value="">Select Frequency</option>

            @php

                $frequencies = [

                    'Daily',
                    'Weekly',
                    'Monthly',
                    'On-demand',
                    'Shift'

                ];

            @endphp

            @foreach($frequencies as $frequency)

                <option value="{{ $frequency }}"
                    {{ old('frequency', $report->frequency ?? '') == $frequency ? 'selected' : '' }}>

                    {{ $frequency }}

                </option>

            @endforeach

        </select>

        @error('frequency')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

    {{-- Priority --}}

    <div class="col-md-2 mb-3">

        <label class="form-label fw-bold">
            Priority
        </label>

        <select name="priority"
                class="form-control">

            @php

                $priorities = [

                    'Low',
                    'Medium',
                    'High',
                    'Critical'

                ];

            @endphp

            @foreach($priorities as $priority)

                <option value="{{ $priority }}"
                    {{ old('priority', $report->priority ?? '') == $priority ? 'selected' : '' }}>

                    {{ $priority }}

                </option>

            @endforeach

        </select>

    </div>

    {{-- Description --}}

    <div class="col-md-6 mb-3">

        <label class="form-label fw-bold">
            Description
        </label>

        <textarea name="description"
                  rows="3"
                  class="form-control">{{ old('description', $report->description ?? '') }}</textarea>

    </div>

    {{-- KPIs / Metrics --}}

    <div class="col-md-6 mb-3">

        <label class="form-label fw-bold">
            KPIs / Output Metrics
        </label>

        <textarea name="kpis_metrics"
                  rows="3"
                  class="form-control">{{ old('kpis_metrics', $report->kpis_metrics ?? '') }}</textarea>

    </div>

    {{-- Source Tables --}}

    <div class="col-md-4 mb-3">

        <label class="form-label fw-bold">
            Source Tables
        </label>

        <input type="text"
               name="source_tables"
               class="form-control"
               value="{{ old('source_tables', $report->source_tables ?? '') }}">

    </div>

    {{-- Primary Filter --}}

    <div class="col-md-4 mb-3">

        <label class="form-label fw-bold">
            Primary Filter
        </label>

        <input type="text"
               name="primary_filter"
               class="form-control"
               value="{{ old('primary_filter', $report->primary_filter ?? '') }}">

    </div>

    {{-- Default Sort --}}

    <div class="col-md-4 mb-3">

        <label class="form-label fw-bold">
            Default Sort
        </label>

        <input type="text"
               name="default_sort"
               class="form-control"
               value="{{ old('default_sort', $report->default_sort ?? '') }}">

    </div>

    {{-- Output Format --}}

    <div class="col-md-4 mb-3">

        <label class="form-label fw-bold">
            Output Format
        </label>

        <select name="output_format"
                class="form-control">

            @php

                $formats = [

                    'PDF',
                    'Excel',
                    'CSV',
                    'Dashboard',
                    'Print'

                ];

            @endphp

            <option value="">Select Format</option>

            @foreach($formats as $format)

                <option value="{{ $format }}"
                    {{ old('output_format', $report->output_format ?? '') == $format ? 'selected' : '' }}>

                    {{ $format }}

                </option>

            @endforeach

        </select>

    </div>

    {{-- Status --}}

    <div class="col-md-2 mb-3">

        <label class="form-label fw-bold">
            Status
        </label>

        <select name="status"
                class="form-control">

            <option value="1"
                {{ old('status', $report->status ?? 1) == 1 ? 'selected' : '' }}>

                Active

            </option>

            <option value="0"
                {{ old('status', $report->status ?? 1) == 0 ? 'selected' : '' }}>

                Inactive

            </option>

        </select>

    </div>

</div>

<div class="mt-3">

    <button type="submit"
            class="btn btn-success">

        Save Report

    </button>

    <a href="{{ route('report-catalogues.index') }}"
       class="btn btn-secondary">

        Back

    </a>

</div>