{{-- resources/views/grn/index.blade.php --}}

@extends('auth.layouts.app')

@section('content')

<style>
    .card-custom {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header-custom {
        background: #343a40;
        color: #fff;
        padding: 15px 20px;
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .btn-sm {
        border-radius: 6px;
    }
</style>

<div class="container mt-4">

    <div class="card shadow card-custom">

        <div class="card-header-custom d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                <i class="fa fa-list me-2"></i> GRN List
            </h4>

            <a href="{{ route('grn.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Add GRN
            </a>

        </div>

        <div class="card-body">

     @if(session('success'))

<div class="alert alert-success alert-dismissible fade show" role="alert">
    
    {{ session('success') }}

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

@endif
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>GRN No</th>
                            <th>GRN Date</th>
                            <th>Supplier Name</th>
                            <th>Invoice No</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($grns as $key => $grn)

                            <tr>

                                <td>{{ $grns->firstItem() + $key }}</td>

                                <td>{{ $grn->grn_no }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($grn->grn_date)->format('d-m-Y') }}
                                </td>

                                <td>{{ $grn->supplier_name }}</td>

                                <td>{{ $grn->invoice_no }}</td>

                                <td>₹ {{ number_format($grn->grand_total, 2) }}</td>

                                <td>
                                    @if($grn->payment_status == 'Paid')

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif($grn->payment_status == 'Pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @else

                                        <span class="badge bg-info">
                                            Partial
                                        </span>

                                    @endif
                                </td>

                              

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    No GRN Records Found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $grns->links() }}
            </div>

        </div>

    </div>

</div>

@endsection