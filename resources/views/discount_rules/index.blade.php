@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    <div class="card border-0 shadow-lg">

        {{-- Header --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-percent me-2"></i>
                    Discount Approval Workflow
                </h4>

                <small class="text-light">
                    Manage approval rules for discount authorization
                </small>
            </div>

            <a href="{{ route('discount-rules.create') }}"
            class="btn btn-light text-primary fw-bold shadow-sm">

                <i class="fas fa-plus-circle me-1"></i>
                Add Rule
            </a>

        </div>

        {{-- Body --}}
        <div class="card-body bg-light">

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fas fa-check-circle me-2"></i>

                    {{ session('success') }}

                    <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark text-center">

                        <tr>

                            <th width="8%">Rule ID</th>

                            <th width="18%">Condition</th>

                            <th width="10%">Threshold</th>

                            <th width="15%">Approval Required</th>

                            <th width="12%">OTP / Password</th>

                            <th width="8%">Audit</th>

                            <th width="15%">Email Alert</th>

                            <th width="20%">Remarks</th>

                            <th width="12%">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($rules as $rule)

                        <tr>

                            {{-- Rule ID --}}
                            <td class="text-center fw-bold text-primary">
                                {{ $rule->rule_id }}
                            </td>

                            {{-- Condition --}}
                            <td>
                                {{ $rule->condition }}
                            </td>

                            {{-- Threshold --}}
                            <td class="text-center">

                                <span class="badge bg-warning text-dark px-3 py-2">

                                    {{ $rule->threshold }}

                                </span>

                            </td>

                            {{-- Approval --}}
                            <td class="text-center">

                                @if($rule->approval_required == 'Admin Only')

                                    <span class="badge bg-danger px-3 py-2">
                                        {{ $rule->approval_required }}
                                    </span>

                                @elseif($rule->approval_required == 'Manager + Admin')

                                    <span class="badge bg-dark px-3 py-2">
                                        {{ $rule->approval_required }}
                                    </span>

                                @else

                                    <span class="badge bg-primary px-3 py-2">
                                        {{ $rule->approval_required }}
                                    </span>

                                @endif

                            </td>

                            {{-- OTP --}}
                            <td class="text-center">

                                @if($rule->otp_password == 'No')

                                    <span class="badge bg-success">
                                        No
                                    </span>

                                @else

                                    <span class="badge bg-info text-dark">
                                        {{ $rule->otp_password }}
                                    </span>

                                @endif

                            </td>

                            {{-- Audit --}}
                            <td class="text-center">

                                @if($rule->audit_log == 1)

                                    <span class="badge bg-success">
                                        Yes
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        No
                                    </span>

                                @endif

                            </td>

                            {{-- Email --}}
                            <td>

                                @if($rule->email_alert_to)

                                    <small class="text-primary fw-bold">

                                        <i class="fas fa-envelope me-1"></i>

                                        {{ $rule->email_alert_to }}

                                    </small>

                                @else

                                    <span class="text-muted">—</span>

                                @endif

                            </td>

                            {{-- Remarks --}}
                            <td>

                                <small class="text-muted">

                                    {{ $rule->remarks }}

                                </small>

                            </td>

                            {{-- Action --}}
                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('discount-rules.edit',$rule->id) }}"
                                    class="btn btn-warning btn-sm shadow-sm">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    {{-- Delete --}}
                                    <form method="POST"
                                    action="{{ route('discount-rules.destroy',$rule->id) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                        class="btn btn-danger btn-sm shadow-sm"
                                        onclick="return confirm('Are you sure to delete this rule?')">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="9" class="text-center text-muted py-4">

                                <i class="fas fa-folder-open fa-2x mb-2"></i>

                                <br>

                                No Discount Approval Rules Found

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