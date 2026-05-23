@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Create Discount Approval Rule</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('discount-rules.store') }}">

                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Condition</label>
                        <input type="text" name="condition" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Threshold</label>
                        <input type="text" name="threshold" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Approval Required</label>

                        <select name="approval_required" class="form-control">

                            <option>Auto / Cashier</option>
                            <option>Manager OTP</option>
                            <option>Manager + Admin</option>
                            <option>Admin Only</option>

                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>OTP / Password</label>

                        <select name="otp_password" class="form-control">

                            <option>No</option>
                            <option>Manager OTP</option>
                            <option>Both OTPs</option>
                            <option>Admin OTP</option>

                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Audit Log</label>

                        <select name="audit_log" class="form-control">

                            <option value="1">Yes</option>
                            <option value="0">No</option>

                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Email Alert To</label>
                        <input type="email" name="email_alert_to" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>

                </div>

                <button class="btn btn-success">
                    Save Rule
                </button>

            </form>

        </div>
    </div>
</div>

@endsection