@extends('auth.layouts.app')

@section('content')

<style>
    .supplier-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .supplier-header {
        background: linear-gradient(135deg,#6f42c1,#4e73df);
        color: #fff;
        padding: 18px 25px;
    }

    .supplier-header h4 {
        margin: 0;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .section-title {
        background: #f8f9fc;
        padding: 10px 15px;
        border-left: 5px solid #4e73df;
        margin-bottom: 20px;
        border-radius: 8px;
        font-weight: 700;
        color: #4e73df;
        text-transform: uppercase;
        font-size: 14px;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        min-height: 45px;
        border: 1px solid #dcdfe8;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: none;
        border-color: #4e73df;
    }

    textarea.form-control {
        min-height: 90px;
    }

    .btn-save {
        background: linear-gradient(135deg,#4e73df,#224abe);
        color: #fff;
        border: none;
        padding: 12px 35px;
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-save:hover {
        background: linear-gradient(135deg,#224abe,#1b3c9e);
        color: white;
    }

    .required {
        color: red;
    }
</style>

<div class="container-fluid mt-4">

    <div class="card supplier-card">

        <div class="supplier-header d-flex justify-content-between align-items-center">
            <h4>Supplier Master</h4>

            <a href="{{ route('supp_mast_form') }}" class="btn btn-light btn-sm">
                <i class="fa fa-list"></i> Supplier List
            </a>
        </div>

        <div class="card-body">

<form id="supplierForm"
      action="{{ route('supp_mast_store') }}"
      method="POST">                @csrf

                <!-- BASIC DETAILS -->
                <div class="section-title">
                    Basic Supplier Details
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Supplier ID <span class="required">*</span>
                        </label>

                        <input type="text"
                               name="supplier_id"
                               class="form-control"
                               value="{{ $suppId ?? '' }}"
                               readonly>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="form-label">
                            Supplier Name <span class="required">*</span>
                        </label>

                        <input type="text"
                               name="supplier_name"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Trade / Brand Name
                        </label>

                        <input type="text"
                               name="trade_brand_name"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Category
                        </label>

                        <select name="category" class="form-select">
                            <option value="">Select Category</option>
                            <option value="RawMaterial">Raw Material</option>
                            <option value="Packaging">Packaging</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Vegetables">Vegetables</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Stationary">Stationary</option>
                            <option value="CleaningMaterial"> Material</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                </div>

                <!-- CONTACT DETAILS -->
                <div class="section-title mt-4">
                    Contact Information
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Contact Person</label>

                        <input type="text"
                               name="contact_person"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Designation</label>

                        <input type="text"
                               name="designation"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Mobile No</label>

                        <input type="text"
                               name="mobile_no"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Alt. Phone</label>

                        <input type="text"
                               name="alt_phone"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email ID</label>

                        <input type="email"
                               name="email_id"
                               class="form-control">
                    </div>

                </div>

                <!-- ADDRESS DETAILS -->
                <div class="section-title mt-4">
                    Address Information
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address Line 1</label>

                        <textarea name="address_line1"
                                  class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address Line 2</label>

                        <textarea name="address_line2"
                                  class="form-control"></textarea>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>

                        <input type="text"
                               name="city"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">State</label>

                        <input type="text"
                               name="state"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pincode</label>

                        <input type="text"
                               name="pincode"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Country</label>

                        <input type="text"
                               name="country"
                               value="India"
                               class="form-control">
                    </div>

                </div>

                <!-- TAX DETAILS -->
                <div class="section-title mt-4">
                    Tax & Legal Information
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">GSTIN</label>

                        <input type="text"
                               name="gstin"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">PAN</label>

                        <input type="text"
                               name="pan"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">FSSAI License No.</label>

                        <input type="text"
                               name="fssai_license_no"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">FSSAI Expiry</label>

                        <input type="date"
                               name="fssai_expiry"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">MSME / Udyam No.</label>

                        <input type="text"
                               name="msme_udyam_no"
                               class="form-control">
                    </div>

                </div>

                <!-- BANK DETAILS -->
                <div class="section-title mt-4">
                    Bank Details
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Bank Name</label>

                        <input type="text"
                               name="bank_name"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Bank Account No.</label>

                        <input type="text"
                               name="bank_account_no"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">IFSC Code</label>

                        <input type="text"
                               name="ifsc_code"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Account Holder Name</label>

                        <input type="text"
                               name="account_holder_name"
                               class="form-control">
                    </div>

                </div>

                <!-- PAYMENT SETTINGS -->
                <div class="section-title mt-4">
                    Payment & Credit Settings
                </div>

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Payment Terms</label>

                        <select name="payment_terms" class="form-select">
                            <option value="">Select</option>
                            <option value="C">Cash</option>
                            <option value="7">7 Days</option>
                            <option value="15">15 Days</option>
                            <option value="30">30 Days</option>
                            <option value="45">45 Days</option>
                            <option value="60">60 Days</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Credit Limit (₹)</label>

                        <input type="number"
                               step="0.01"
                               name="credit_limit"
                               class="form-control">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Currency</label>

                        <select name="currency" class="form-select">
                            <option value="INR">INR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">TDS Applicable</label>

                        <select name="tds_applicable"
                                id="tds_applicable"
                                class="form-select">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">TDS Rate (%)</label>

                        <input type="number"
                               step="0.01"
                               name="tds_rate"
                               id="tds_rate"
                               class="form-control"
                               readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lead Time (Days)</label>

                        <input type="number"
                               name="lead_time_days"
                               class="form-control">
                    </div>

                </div>

                <!-- STATUS -->
                <div class="section-title mt-4">
                    Status & Audit Information
                </div>

                <div class="row">

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Rating</label>

                        <select name="rating" class="form-select">
                            <option value="1">1 Star</option>
                            <option value="2">2 Star</option>
                            <option value="3">3 Star</option>
                            <option value="4">4 Star</option>
                            <option value="5">5 Star</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>

                        <select name="status" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Blocked">Blocked</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Onboarded On</label>

                        <input type="date"
                               name="onboarded_on"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Onboarded By</label>

                        <input type="text"
                               name="onboarded_by"
                               value="{{ Auth::user()->name ?? '' }}"
                               class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Remarks</label>

                        <textarea name="remarks"
                                  class="form-control"></textarea>
                    </div>

                </div>

                <!-- BUTTONS -->
                <div class="text-end mt-4">

                    <button type="reset" class="btn btn-secondary px-4">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-save">
                        <i class="fa fa-save"></i> Save Supplier
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $('#tds_applicable').on('change', function () {

        if ($(this).val() === 'Yes') {
            $('#tds_rate').prop('readonly', false);
        } else {
            $('#tds_rate').prop('readonly', true);
            $('#tds_rate').val('');
        }

    });
</script>
<script>

$(document).ready(function () {

    $('#supplierForm').on('submit', function (e) {

        e.preventDefault();

        $('.error-text').html('');
        $('.form-control').removeClass('is-invalid');
        $('.form-select').removeClass('is-invalid');

        let formData = new FormData(this);

        $.ajax({

            url: $(this).attr('action'),

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            beforeSend: function () {

                $('#saveBtn')
                    .html('<i class="fa fa-spinner fa-spin"></i> Saving...')
                    .prop('disabled', true);

            },

            success: function (response) {

                toastr.success(response.message);

                $('#supplierForm')[0].reset();

                $('#saveBtn')
                    .html('<i class="fa fa-save"></i> Save Supplier')
                    .prop('disabled', false);

            },

            error: function (xhr) {

                $('#saveBtn')
                    .html('<i class="fa fa-save"></i> Save Supplier')
                    .prop('disabled', false);

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {

                        $('.' + key + '_error')
                            .html(value[0]);

                        $('[name="' + key + '"]')
                            .addClass('is-invalid');

                    });

                    toastr.error(
                        xhr.responseJSON.message
                    );

                } else {

                    toastr.error(
                        'Something went wrong.'
                    );

                }

            }

        });

    });

});

</script>

@endsection