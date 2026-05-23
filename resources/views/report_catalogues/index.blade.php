@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Report Master</h4>

            <a href="{{ route('report-catalogues.create') }}"
               class="btn btn-light btn-sm">
                Add Report
            </a>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Code</th>
                            <th>Report Name</th>
                            <th>Category</th>
                            <th>Frequency</th>
                            <th>Description</th>
                            <th>KPIs</th>
                            <th>Source Tables</th>
                            <th>Output Format</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th width="170">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($reports as $report)

                            <tr>

                                <td>{{ $report->report_code }}</td>

                                <td>{{ $report->report_name }}</td>

                                <td>{{ $report->category }}</td>

                                <td>{{ $report->frequency }}</td>

                                <td>{{ $report->description }}</td>

                                <td>{{ $report->kpis_metrics }}</td>

                                <td>{{ $report->source_tables }}</td>

                                <td>{{ $report->output_format }}</td>

                                <td>{{ $report->priority }}</td>

                                <td>

                                    @if($report->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route('report-catalogues.edit', $report->id) }}"
                                       class="btn btn-primary btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('report-catalogues.destroy', $report->id) }}"
                                          method="POST"
                                          style="display:inline-block">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this report?')">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="11" class="text-center">
                                    No Data Found
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

                <div class="mt-3">

                    {{ $reports->links() }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection