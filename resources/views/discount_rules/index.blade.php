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
                     <th>Rule ID</th>
                     <th>Condition</th>
                     <th>Threshold</th>
                     <th>Approval Required</th>
                     <th>OTP / Password</th>
                     <th>Approval Status</th>
                     <th>Action</th>
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
                     {{-- Approval Required --}}
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
                     {{-- OTP / Password --}}
                     <td class="text-center">
                        @if($rule->otp_password == 'No')
                        <span class="badge bg-success">No</span>
                        @else
                        <span class="badge bg-info text-dark">
                        {{ $rule->otp_password }}
                        </span>
                        @endif
                     </td>
                     {{-- Approval Status --}}
                     <td class="text-center">
                        @if($rule->approval_status == 'APPROVED')
                        <span class="badge bg-success">
                        APPROVED
                        </span>
                        @elseif($rule->approval_status == 'PENDING')
                        <span class="badge bg-warning text-dark">
                        PENDING
                        </span>
                        @elseif($rule->approval_status == 'REJECTED')
                        <span class="badge bg-danger">
                        REJECTED
                        </span>
                        @else
                        <span class="badge bg-secondary">
                        NOT STARTED
                        </span>
                        @endif
                     </td>
                     {{-- Action --}}
                     <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                           <a href="{{ route('discount-rules.edit',$rule->id) }}"
                              class="btn btn-warning btn-sm shadow-sm">
                           <i class="fas fa-edit"></i>
                           </a>
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
                     <td colspan="12" class="text-center text-muted py-4">
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