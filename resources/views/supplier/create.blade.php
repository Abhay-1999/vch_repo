@extends('auth.layouts.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@400;500;600&display=swap');

    :root {
        --brand: #7B2D00;
        --brand-light: #FFF4EE;
        --brand-mid: #C24B1A;
        --accent: #E8A87C;
        --section-border: #F3D5C0;
        --label-color: #7B2D00;
        --input-focus: #C24B1A;
    }

    body { font-family: 'DM Sans', sans-serif; background: #FDF6F2; }

    .supp-wrapper { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }

    .supp-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 32px rgba(123,45,0,0.08);
        overflow: hidden;
    }

    .supp-header {
        background: linear-gradient(135deg, #7B2D00 0%, #C24B1A 60%, #E8A87C 100%);
        padding: 1.6rem 2rem;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .supp-header .header-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,0.18);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff;
    }

    .supp-header h4 {
        font-family: 'Playfair Display', serif;
        color: #fff; margin: 0; font-size: 1.4rem;
    }

    .supp-header p { color: rgba(255,255,255,0.75); margin: 0; font-size: 13px; }

    .supp-body { padding: 2rem; }

    /* Section Blocks */
    .form-section {
        margin-bottom: 2rem;
        border: 1.5px solid var(--section-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .section-title {
        background: var(--brand-light);
        border-bottom: 1.5px solid var(--section-border);
        padding: 0.65rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--brand);
    }

    .section-title i { font-size: 16px; }

    .section-body { padding: 1.2rem 1.2rem 0.4rem; }

    /* Labels */
    label.ctrl-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--label-color);
        margin-bottom: 5px;
    }

    label.ctrl-label .req { color: #C24B1A; margin-left: 2px; }

    /* Inputs */
    .form-control, .form-select {
        border: 1.5px solid #E8D5C4;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.9rem;
        font-family: 'DM Sans', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #FFFAF7;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--input-focus);
        box-shadow: 0 0 0 3px rgba(194,75,26,0.12);
        background: #fff;
        outline: none;
    }

    .form-control.required-field.is-invalid { border-color: #dc3545; }

    /* Save / Reset buttons */
    .btn-save {
        background: linear-gradient(135deg, #7B2D00, #C24B1A);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 36px;
        font-weight: 600;
        font-size: 0.95rem;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.1s;
        display: inline-flex; align-items: center; gap: 8px;
    }

    .btn-save:hover { opacity: 0.9; }
    .btn-save:active { transform: scale(0.98); }
    .btn-save:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-reset {
        background: #fff;
        color: #7B2D00;
        border: 1.5px solid #C24B1A;
        border-radius: 10px;
        padding: 11px 28px;
        font-weight: 600;
        font-size: 0.95rem;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-reset:hover { background: var(--brand-light); }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1.5px solid var(--section-border);
        margin-top: 1rem;
    }

    /* Alert */
    .supp-alert {
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 1.2rem;
        font-size: 0.9rem;
        display: flex; align-items: flex-start; gap: 10px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn { from { opacity:0; transform: translateY(-6px); } to { opacity:1; transform:none; } }

    .supp-alert.success { background: #EEFAF4; border: 1.5px solid #6FCF97; color: #1B6B3A; }
    .supp-alert.error   { background: #FFF0F0; border: 1.5px solid #EB5757; color: #7B1B1B; }
    .supp-alert i { margin-top: 2px; flex-shrink: 0; }
</style>

<div class="supp-wrapper">
    <div class="supp-card">

        {{-- Header --}}
        <div class="supp-header">
            <div class="header-icon"><i class="fas fa-utensils"></i></div>
            <div>
                <h4>Supplier Master</h4>
                <p>Restaurant &amp; Food Service Vendor Registration</p>
            </div>
        </div>

        <div class="supp-body">
            <div id="alertBox"></div>

            <form id="suppForm">
                @csrf

                {{-- 1. Basic Info --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-store"></i> Supplier Basic Information
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="ctrl-label">Supplier Name <span class="req">*</span></label>
                                <input type="text" class="form-control required-field" name="supp_name" placeholder="e.g. Fresh Farms Pvt Ltd">
                            </div>
                            <div class="col-md-2">
                                <label class="ctrl-label">Supplier Code</label>
                                <input type="text" class="form-control" name="supp_code" placeholder="Auto / Manual">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Supplier Type</label>
                                <select class="form-control form-select" name="supp_type">
                                    <option value="">-- Select Type --</option>
                                    <option value="Local">Local</option>
                                    <option value="Outside">Outside</option>
                                    <option value="Manufacturer">Manufacturer</option>
                                    <option value="Wholesaler">Wholesaler</option>
                                    <option value="Farmer">Farmer / Direct Producer</option>
                                    <option value="Import">Importer</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">GST No</label>
                                <input type="text" class="form-control" name="gst_no" placeholder="27AXXXX0000X1Z5" maxlength="15">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Restaurant-Specific --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-drumstick-bite"></i> Food Supply Details
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="ctrl-label">Supply Category <span class="req">*</span></label>
                                <select class="form-control form-select required-field" name="supply_category">
                                    <option value="">-- Select Category --</option>
                                    <option value="Vegetables">Vegetables &amp; Greens</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Dairy">Dairy &amp; Eggs</option>
                                    <option value="Meat">Meat &amp; Poultry</option>
                                    <option value="Seafood">Seafood &amp; Fish</option>
                                    <option value="Dry Goods">Dry Goods &amp; Spices</option>
                                    <option value="Beverages">Beverages &amp; Drinks</option>
                                    <option value="Bakery">Bakery &amp; Confectionery</option>
                                    <option value="Oils">Oils &amp; Condiments</option>
                                    <option value="Packaging">Packaging &amp; Disposables</option>
                                    <option value="Cleaning">Cleaning &amp; Hygiene</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">FSSAI License No</label>
                                <input type="text" class="form-control" name="fssai_no" placeholder="14-digit FSSAI number" maxlength="14">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">FSSAI Expiry Date</label>
                                <input type="date" class="form-control" name="fssai_expiry">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Delivery Days</label>
                                <select class="form-control form-select" name="delivery_days">
                                    <option value="">-- Select --</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Alternate">Alternate Days</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="On Order">On Order Only</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Delivery Time Slot</label>
                                <select class="form-control form-select" name="delivery_slot">
                                    <option value="">-- Select --</option>
                                    <option value="Early Morning">Early Morning (4–7 AM)</option>
                                    <option value="Morning">Morning (7–11 AM)</option>
                                    <option value="Afternoon">Afternoon (12–4 PM)</option>
                                    <option value="Evening">Evening (5–9 PM)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Minimum Order (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="min_order_value" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Lead Time (Days)</label>
                                <input type="number" class="form-control" name="lead_time_days" placeholder="e.g. 1">
                            </div>
                            <div class="col-md-6">
                                <label class="ctrl-label">Items / Products Supplied</label>
                                <input type="text" class="form-control" name="items_supplied" placeholder="e.g. Onion, Tomato, Potato, Chicken Breast">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Quality Grade</label>
                                <select class="form-control form-select" name="quality_grade">
                                    <option value="">-- Select --</option>
                                    <option value="A">Grade A (Premium)</option>
                                    <option value="B">Grade B (Standard)</option>
                                    <option value="C">Grade C (Economy)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Is Organic / Certified?</label>
                                <select class="form-control form-select" name="is_organic">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes – Organic Certified</option>
                                    <option value="Partial">Partially Organic</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Address --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Address Details
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="ctrl-label">Address Line 1</label>
                                <input type="text" class="form-control" name="supp_add1" placeholder="Shop / Plot No, Street Name">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">Address Line 2</label>
                                <input type="text" class="form-control" name="supp_add2" placeholder="Area / Locality">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Market / Mandi</label>
                                <input type="text" class="form-control" name="market_name" placeholder="e.g. Azadpur Mandi">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">City</label>
                                <input type="text" class="form-control" name="city" placeholder="City">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">State</label>
                                <input type="text" class="form-control" name="state" placeholder="State">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Pincode</label>
                                <input type="text" class="form-control" name="pincode" maxlength="6" placeholder="6-digit">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Country</label>
                                <input type="text" class="form-control" name="country" value="India" placeholder="India">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Contact --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-phone-alt"></i> Contact Information
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="ctrl-label">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person" placeholder="Owner / Manager Name">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Mobile No <span class="req">*</span></label>
                                <input type="text" class="form-control required-field" name="contact_no" id="contact_no" maxlength="10" placeholder="10-digit number">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Alternate Mobile</label>
                                <input type="text" class="form-control" name="alt_contact_no" id="alt_contact_no" maxlength="10" placeholder="Optional">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">WhatsApp No</label>
                                <input type="text" class="form-control" name="whatsapp_no" id="whatsapp_no" maxlength="10" placeholder="For order updates">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="supplier@email.com">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">PAN No</label>
                                <input type="text" class="form-control" name="pan_no" placeholder="AXXXX0000X" maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. Financial --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-rupee-sign"></i> Financial Details
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="ctrl-label">Opening Balance (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="opening_balance" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Credit Limit (₹)</label>
                                <input type="number" step="0.01" class="form-control" name="credit_limit" placeholder="0.00">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Payment Terms (Days)</label>
                                <input type="number" class="form-control" name="payment_terms" placeholder="e.g. 15">
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Payment Mode</label>
                                <select class="form-control form-select" name="payment_mode">
                                    <option value="">-- Select --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="NEFT">NEFT / RTGS</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Credit">Credit Account</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Discount % (if any)</label>
                                <input type="number" step="0.01" max="100" class="form-control" name="discount_pct" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 6. Bank --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-university"></i> Bank Details
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="ctrl-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" placeholder="e.g. State Bank of India">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">Account No</label>
                                <input type="text" class="form-control" name="account_no" placeholder="Account Number">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">IFSC Code</label>
                                <input type="text" class="form-control" name="ifsc" placeholder="e.g. SBIN0001234" maxlength="11">
                            </div>
                            <div class="col-md-4">
                                <label class="ctrl-label">UPI ID</label>
                                <input type="text" class="form-control" name="upi_id" placeholder="supplier@upi">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 7. Additional --}}
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-clipboard-list"></i> Additional Info
                    </div>
                    <div class="section-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="ctrl-label">Active Status</label>
                                <select class="form-control form-select" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Blacklisted">Blacklisted</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="ctrl-label">Rating (1–5)</label>
                                <select class="form-control form-select" name="supplier_rating">
                                    <option value="">-- Rate Supplier --</option>
                                    <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                    <option value="4">⭐⭐⭐⭐ Good</option>
                                    <option value="3">⭐⭐⭐ Average</option>
                                    <option value="2">⭐⭐ Poor</option>
                                    <option value="1">⭐ Very Poor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="ctrl-label">Remarks / Notes</label>
                                <input type="text" class="form-control" name="remark" placeholder="e.g. Fresh delivery guaranteed, No preservatives">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <button type="button" class="btn-reset" id="resetBtn">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn-save" id="saveBtn">
                        <i class="fas fa-save"></i> Save Supplier
                    </button>
                </div>

            </form>
        </div>{{-- supp-body --}}
    </div>{{-- supp-card --}}
</div>{{-- supp-wrapper --}}


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    /* ── Phone fields: digits only, max 10 ── */
    $('#contact_no, #alt_contact_no, #whatsapp_no').on('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
    });

    /* ── FSSAI: digits only, max 14 ── */
    $('[name="fssai_no"]').on('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 14);
    });

    /* ── Pincode: digits only, max 6 ── */
    $('[name="pincode"]').on('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
    });

    /* ── PAN: uppercase ── */
    $('[name="pan_no"]').on('input', function () {
        this.value = this.value.toUpperCase().slice(0, 10);
    });

    /* ── IFSC: uppercase ── */
    $('[name="ifsc"]').on('input', function () {
        this.value = this.value.toUpperCase().slice(0, 11);
    });

    /* ── GST: uppercase ── */
    $('[name="gst_no"]').on('input', function () {
        this.value = this.value.toUpperCase().slice(0, 15);
    });

    /* ── Reset ── */
    $('#resetBtn').on('click', function () {
        $('#suppForm')[0].reset();
        $('.required-field').removeClass('is-invalid');
        $('#alertBox').html('');
    });

    /* ── Submit ── */
    $('#suppForm').on('submit', function (e) {
        e.preventDefault();

        let valid = true;
        $('.required-field').each(function () {
            if ($(this).val().trim() === '') {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            showAlert('error', '<i class="fas fa-exclamation-circle"></i> Please fill all required fields marked with *');
            return;
        }

        $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '{{ route("supp_mast_store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('.is-invalid').removeClass('is-invalid');
                showAlert('success', '<i class="fas fa-check-circle"></i> ' + response.message);
                $('#suppForm')[0].reset();
                setTimeout(function () {
                    $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Supplier');
                }, 3000);
            },
            error: function (xhr) {
                $('.is-invalid').removeClass('is-invalid');
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let msg = "<i class='fas fa-exclamation-triangle'></i> <ul style='margin:6px 0 0 18px;padding:0;'>";
                    $.each(errors, function (field, messages) {
                        $('[name="' + field + '"]').addClass('is-invalid');
                        msg += '<li>' + messages[0] + '</li>';
                    });
                    msg += '</ul>';
                    showAlert('error', msg);
                } else {
                    showAlert('error', '<i class="fas fa-times-circle"></i> Something went wrong. Please try again.');
                }
                $('#saveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Supplier');
            }
        });
    });

    function showAlert(type, message) {
        let html = '<div class="supp-alert ' + type + '">' + message + '</div>';
        $('#alertBox').html(html);
        $('html, body').animate({ scrollTop: 0 }, 400);
        setTimeout(function () {
            $('#alertBox .supp-alert').fadeOut(400, function () { $(this).remove(); });
        }, 4000);
    }
});
</script>

@endsection