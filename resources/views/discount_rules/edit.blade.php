@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-warning text-dark">
            <h4>Edit Discount Approval Rule</h4>
        </div>

        <div class="card-body">

            <form method="POST"
            action="{{ route('discount-rules.update',$rule->id) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Rule ID</label>

                        <input type="text"
                        class="form-control"
                        value="{{ $rule->rule_id }}"
                        readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Condition</label>

                        <input type="text"
                        name="condition"
                        class="form-control"
                        value="{{ $rule->condition }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Threshold</label>

                        <input type="text"
                        name="threshold"
                        class="form-control"
                        value="{{ $rule->threshold }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Approval Required</label>

                        <select name="approval_required"
                        class="form-control">

                            <option value="Auto / Cashier"
                            {{ $rule->approval_required == 'Auto / Cashier' ? 'selected' : '' }}>
                                Auto / Cashier
                            </option>

                            <option value="Manager OTP"
                            {{ $rule->approval_required == 'Manager OTP' ? 'selected' : '' }}>
                                Manager OTP
                            </option>

                            <option value="Manager + Admin"
                            {{ $rule->approval_required == 'Manager + Admin' ? 'selected' : '' }}>
                                Manager + Admin
                            </option>

                            <option value="Admin Only"
                            {{ $rule->approval_required == 'Admin Only' ? 'selected' : '' }}>
                                Admin Only
                            </option>

                        </select>
                    </div>

                    <div class="col-md-4 mb-3">

                        <label>OTP / Password</label>

                        <select name="otp_password"
                        class="form-control">

                            <option value="No"
                            {{ $rule->otp_password == 'No' ? 'selected' : '' }}>
                                No
                            </option>

                            <option value="Manager OTP"
                            {{ $rule->otp_password == 'Manager OTP' ? 'selected' : '' }}>
                                Manager OTP
                            </option>

                            <option value="Both OTPs"
                            {{ $rule->otp_password == 'Both OTPs' ? 'selected' : '' }}>
                                Both OTPs
                            </option>

                            <option value="Admin OTP"
                            {{ $rule->otp_password == 'Admin OTP' ? 'selected' : '' }}>
                                Admin OTP
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Audit Log</label>

                        <select name="audit_log"
                        class="form-control">

                            <option value="1"
                            {{ $rule->audit_log == 1 ? 'selected' : '' }}>
                                Yes
                            </option>

                            <option value="0"
                            {{ $rule->audit_log == 0 ? 'selected' : '' }}>
                                No
                            </option>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Email Alert To</label>

                        <input type="email"
                        name="email_alert_to"
                        class="form-control"
                        value="{{ $rule->email_alert_to }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Status</label>

                        <select name="status"
                        class="form-control">

                            <option value="1"
                            {{ $rule->status == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                            {{ $rule->status == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>
                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Remarks</label>

                        <textarea name="remarks"
                        class="form-control"
                        rows="3">{{ $rule->remarks }}</textarea>

                    </div>

                </div>

                <button class="btn btn-success">
                    Update Rule
                </button>

                <a href="{{ route('discount-rules.index') }}"
                class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection