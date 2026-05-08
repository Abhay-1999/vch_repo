@extends('auth.layouts.app')

@section('content')

<style>
    :root{
        --primary:#2563eb;
        --primary-light:#eff6ff;
        --success:#059669;
        --danger:#dc2626;
        --border:#dbe3ef;
        --bg:#f8fafc;
    }

    body{
        background:#f4f7fb;
    }

    .grn-card{
        border:none;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 8px 30px rgba(0,0,0,0.08);
    }

    .grn-header{
        background:linear-gradient(135deg,#2563eb,#1e40af);
        color:#fff;
        padding:18px 25px;
    }

    .section-box{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:18px;
        margin-bottom:22px;
    }

    .section-title{
        font-size:14px;
        font-weight:700;
        color:var(--primary);
        margin-bottom:16px;
        text-transform:uppercase;
        letter-spacing:.5px;
        border-left:4px solid var(--primary);
        padding-left:10px;
    }

    .form-label{
        font-size:12px;
        font-weight:700;
        color:#374151;
        margin-bottom:5px;
    }

    .form-control,
    .form-select{
        border-radius:10px;
        border:1px solid #d1d5db;
        min-height:42px;
        font-size:13px;
    }

    .form-control:focus,
    .form-select:focus{
        border-color:var(--primary);
        box-shadow:0 0 0 3px rgba(37,99,235,.15);
    }

    /* TABLE */

    .table-wrapper{
        overflow:auto;
        border-radius:16px;
        border:1px solid var(--border);
    }

    .material-table{
        width:100%;
        border-collapse:separate;
        border-spacing:0;
        min-width:1900px;
    }

    .material-table thead th{
        background:#1e3a8a;
        color:#fff;
        padding:10px 8px;
        font-size:11px;
        text-align:center;
        white-space:nowrap;
        border:1px solid #1d4ed8;
        position:sticky;
        top:0;
        z-index:5;
    }

    .material-table tbody td{
        border:1px solid #e5e7eb;
        padding:6px;
        vertical-align:top;
        background:#fff;
    }

    .material-table tbody tr:nth-child(even) td{
        background:#f9fbff;
    }

    .material-table input,
    .material-table select{
        width:100%;
        min-width:90px;
        border:1px solid #d1d5db;
        border-radius:8px;
        padding:7px 8px;
        font-size:12px;
        background:#fff;
    }

    .calc-field{
        background:#eef4ff !important;
        font-weight:600;
        color:#1e3a8a;
    }

    .row-group-title{
        background:#dbeafe !important;
        color:#1e3a8a;
        font-size:11px;
        font-weight:700;
        text-align:center;
        text-transform:uppercase;
        letter-spacing:.4px;
    }

    .btn-add-row{
        background:var(--success);
        color:#fff;
        border:none;
        border-radius:10px;
        padding:10px 18px;
        font-size:13px;
        font-weight:600;
    }

    .btn-add-row:hover{
        background:#047857;
    }

    .btn-remove-row{
        background:var(--danger);
        border:none;
        color:#fff;
        border-radius:8px;
        padding:6px 10px;
        font-size:13px;
    }

    .grand-total-bar{
        background:linear-gradient(135deg,#1e40af,#2563eb);
        border-radius:16px;
        padding:18px;
        color:#fff;
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
        gap:15px;
    }

    .total-card{
        background:rgba(255,255,255,.12);
        border-radius:12px;
        padding:14px;
    }

    .total-card small{
        display:block;
        font-size:12px;
        opacity:.9;
    }

    .total-card b{
        font-size:22px;
        display:block;
        margin-top:5px;
    }

    .action-btns .btn{
        border-radius:10px;
        padding:10px 18px;
        font-weight:600;
    }

    /* RESPONSIVE */

    @media(max-width:768px){

        .material-table{
            min-width:2400px;
        }

        .grn-header h4{
            font-size:18px;
        }

    }

    
</style>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-import me-2"></i>Goods Receipt Note (GRN) — Multiple Materials</h5>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('grn.store') }}" method="POST" id="grnForm">
                @csrf

                {{-- ─── Header: GRN, PO, Invoice, Supplier ──────────────────── --}}
                <h6 class="section-title mb-3">GRN & Supplier Header</h6>
                <div class="row g-3 mb-4">

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">GRN No. <span class="text-danger">*</span></label>
                        <input type="text" name="grn_no"
                               class="form-control @error('grn_no') is-invalid @enderror"
                               value="{{ old('grn_no') }}" required>
                        @error('grn_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">GRN Date <span class="text-danger">*</span></label>
                        <input type="date" name="grn_date"
                               class="form-control @error('grn_date') is-invalid @enderror"
                               value="{{ old('grn_date', date('Y-m-d')) }}" required>
                        @error('grn_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">PO No. <span class="text-danger">*</span></label>
                        <input type="text" name="po_no"
                               class="form-control @error('po_no') is-invalid @enderror"
                               value="{{ old('po_no') }}" required>
                        @error('po_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Invoice No. <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_no"
                               class="form-control @error('invoice_no') is-invalid @enderror"
                               value="{{ old('invoice_no') }}" required>
                        @error('invoice_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" name="invoice_date"
                               class="form-control @error('invoice_date') is-invalid @enderror"
                               value="{{ old('invoice_date') }}" required>
                        @error('invoice_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Supplier ID <span class="text-danger">*</span></label>
                        <input type="text" name="supplier_id"
                               class="form-control @error('supplier_id') is-invalid @enderror"
                               value="{{ old('supplier_id') }}" required>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" name="supplier_name"
                               class="form-control @error('supplier_name') is-invalid @enderror"
                               value="{{ old('supplier_name') }}" required>
                        @error('supplier_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Storage Location <span class="text-danger">*</span></label>
                        <input type="text" name="storage_location"
                               class="form-control @error('storage_location') is-invalid @enderror"
                               value="{{ old('storage_location') }}" required>
                        @error('storage_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Received By <span class="text-danger">*</span></label>
                        <input type="text" name="received_by"
                               class="form-control @error('received_by') is-invalid @enderror"
                               value="{{ old('received_by') }}" required>
                        @error('received_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Verified By <span class="text-danger">*</span></label>
                        <input type="text" name="verified_by"
                               class="form-control @error('verified_by') is-invalid @enderror"
                               value="{{ old('verified_by') }}" required>
                        @error('verified_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                {{-- END Header row --}}

                {{-- ─── Material Rows Table ─────────────────────────────────── --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="section-title mb-0">Material Lines</h6>
                    <button type="button" class="btn-add-row" id="addRow">
                        <i class="fas fa-plus me-1"></i> Add Material Row
                    </button>
                </div>

<div class="table-responsive">
                    <table class="table table-borderedmaterial-table " id="materialTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Material Code *</th>
                                <th>Material Name *</th>
                                <th>Batch/Lot No.</th>
                                <th>Mfg Date</th>
                                <th>Expiry Date</th>
                                <th>Qty (Pur UoM) *</th>
                                <th>Pur UoM *</th>
                                <th>Conv. Factor</th>
                                <th>Qty (Base UoM)</th>
                                <th>Base UoM</th>
                                <th>Rate (&#8377;/Pur UoM) *</th>
                                <th>Taxable Val (&#8377;)</th>
                                <th>Disc %</th>
                                <th>Disc Amt (&#8377;)</th>
                                <th>Net Taxable (&#8377;)</th>
                                <th>GST % *</th>
                                <th>CGST (&#8377;)</th>
                                <th>SGST (&#8377;)</th>
                                <th>IGST (&#8377;)</th>
                                <th>Total GST (&#8377;)</th>
                                <th>Other Charges</th>
                                <th>Round-off</th>
                                <th>Total Amt (&#8377;)</th>
                                <th>Eff. Cost/Base</th>
                                <th>QC Status *</th>
                                <th>Accepted Qty</th>
                                <th>Rejected Qty</th>
                                <th>Rejection Reason</th>
                                <th>Payment Status</th>
                                <th>Pay Date</th>
                                <th>Pay Ref.</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="materialBody">
                            {{-- JS will inject rows --}}
                        </tbody>
                    </table>
                </div>

                {{-- Grand Total Bar --}}
                <div class="grand-total-bar mb-4">
                    <div>Total Taxable: <b id="gt_taxable">&#8377;0.00</b></div>
                    <div>Total GST: <b id="gt_gst">&#8377;0.00</b></div>
                    <div>Other Charges: <b id="gt_other">&#8377;0.00</b></div>
                    <div>Grand Total: <b id="gt_total">&#8377;0.00</b></div>
                </div>

                {{-- ─── Payment Header Level ────────────────────────────────── --}}
                <h6 class="section-title mb-3">Payment (Header Level)</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="Unpaid" {{ old('payment_status', 'Unpaid') === 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Partially Paid" {{ old('payment_status') === 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="Paid" {{ old('payment_status') === 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control"
                               value="{{ old('payment_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Payment Reference</label>
                        <input type="text" name="payment_reference" class="form-control"
                               placeholder="UTR / Cheque No."
                               value="{{ old('payment_reference') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Remark (Header)</label>
                        <input type="text" name="remark" class="form-control"
                               value="{{ old('remark') }}">
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('grn.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="button" class="btn btn-warning" onclick="initFirstRow()">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save GRN
                    </button>
                </div>

            </form>
        </div>{{-- card-body --}}
    </div>{{-- card --}}
</div>{{-- container --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let rowIndex = 0;

const GST_OPTIONS = [0, 5, 12, 18, 28].map(function(v) {
    return '<option value="' + v + '">' + v + '%</option>';
}).join('');

const QC_OPTIONS = '<option value="Pending" selected>Pending</option>'
    + '<option value="Pass">Pass</option>'
    + '<option value="Fail">Fail</option>';

const PAY_OPTIONS = '<option value="Unpaid" selected>Unpaid</option>'
    + '<option value="Partially Paid">Partially Paid</option>'
    + '<option value="Paid">Paid</option>';

function makeRow(i) {
    return '<tr data-row="' + i + '">'
        + '<td class="text-center fw-bold row-num" style="min-width:32px">' + (i + 1) + '</td>'
        + '<td><input type="text" name="items[' + i + '][material_code]" required placeholder="MAT-001" style="min-width:90px"></td>'
        + '<td><input type="text" name="items[' + i + '][material_name]" required placeholder="Material Name" style="min-width:130px"></td>'
        + '<td><input type="text" name="items[' + i + '][batch_lot_no]" style="min-width:80px"></td>'
        + '<td><input type="date" name="items[' + i + '][mfg_date]" style="min-width:110px"></td>'
        + '<td><input type="date" name="items[' + i + '][expiry_date]" style="min-width:110px"></td>'
        + '<td><input type="number" step="0.0001" name="items[' + i + '][qty_purchase_uom]" class="qty-pur" required min="0.0001" placeholder="0" style="min-width:80px"></td>'
        + '<td><input type="text" name="items[' + i + '][purchase_uom]" required placeholder="KG" style="min-width:55px"></td>'
        + '<td><input type="number" step="0.0001" name="items[' + i + '][conversion_factor]" class="conv-factor" value="1" min="0.0001" style="min-width:65px"></td>'
        + '<td><input type="number" step="0.0001" name="items[' + i + '][qty_base_uom]" class="calc-field qty-base" readonly style="min-width:80px"></td>'
        + '<td><input type="text" name="items[' + i + '][base_uom]" placeholder="NOS" style="min-width:55px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][rate_per_purchase_uom]" class="rate" required min="0" placeholder="0.00" style="min-width:85px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][taxable_value]" class="calc-field taxable-val" readonly style="min-width:90px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][discount_percent]" class="disc-pct" value="0" min="0" max="100" style="min-width:60px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][discount_amount]" class="calc-field disc-amt" readonly style="min-width:80px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][net_taxable_value]" class="calc-field net-taxable" readonly style="min-width:90px"></td>'
        + '<td><select name="items[' + i + '][gst_rate]" class="gst-rate" required style="min-width:70px">' + GST_OPTIONS + '</select></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][cgst]" class="calc-field cgst" readonly style="min-width:75px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][sgst]" class="calc-field sgst" readonly style="min-width:75px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][igst]" class="igst" value="0" min="0" style="min-width:75px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][total_gst]" class="calc-field total-gst" readonly style="min-width:80px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][other_charges]" class="other-charges" value="0" min="0" style="min-width:80px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][round_off]" class="round-off" value="0" style="min-width:70px"></td>'
        + '<td><input type="number" step="0.01" name="items[' + i + '][total_amount]" class="calc-field total-amt row-total" readonly style="min-width:95px"></td>'
        + '<td><input type="number" step="0.0001" name="items[' + i + '][effective_cost_per_base_uom]" class="calc-field eff-cost" readonly style="min-width:85px"></td>'
        + '<td><select name="items[' + i + '][quality_check]" required style="min-width:80px">' + QC_OPTIONS + '</select></td>'
        + '<td><input type="number" step="0.0001" name="items[' + i + '][accepted_qty_base_uom]" value="0" min="0" style="min-width:80px"></td>'
        + '<td><input type="number" step="0.0001" name="items[' + i + '][rejected_qty_base_uom]" value="0" min="0" style="min-width:80px"></td>'
        + '<td><input type="text" name="items[' + i + '][rejection_reason]" placeholder="If any" style="min-width:110px"></td>'
        + '<td><select name="items[' + i + '][payment_status]" style="min-width:100px">' + PAY_OPTIONS + '</select></td>'
        + '<td><input type="date" name="items[' + i + '][payment_date]" style="min-width:110px"></td>'
        + '<td><input type="text" name="items[' + i + '][payment_reference]" placeholder="UTR/Cheque" style="min-width:100px"></td>'
        + '<td><input type="text" name="items[' + i + '][remark]" style="min-width:100px"></td>'
        + '<td class="text-center"><button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Remove">&times;</button></td>'
        + '</tr>';
}

function addRow() {
    var tbody = document.getElementById('materialBody');
    var tmp = document.createElement('tbody');
    tmp.innerHTML = makeRow(rowIndex++);
    var tr = tmp.querySelector('tr');
    tbody.appendChild(tr);
    attachRowListeners(tr);
    reindex();
}

function removeRow(btn) {
    var tbody = document.getElementById('materialBody');
    if (tbody.rows.length <= 1) {
        alert('At least one material row is required.');
        return;
    }
    btn.closest('tr').remove();
    reindex();
    updateGrandTotal();
}

function reindex() {
    document.querySelectorAll('#materialBody tr').forEach(function(tr, i) {
        tr.querySelector('.row-num').textContent = i + 1;
    });
}

function n(el) { return parseFloat(el ? el.value : 0) || 0; }

function setVal(el, val) {
    if (!el) return;
    el.value = isNaN(val) ? '0.00' : val.toFixed(el.step === '0.0001' ? 4 : 2);
}

function calcRow(tr) {
    var qtyPur   = n(tr.querySelector('.qty-pur'));
    var conv     = n(tr.querySelector('.conv-factor')) || 1;
    var rate     = n(tr.querySelector('.rate'));
    var discPct  = n(tr.querySelector('.disc-pct'));
    var gstRate  = parseFloat(tr.querySelector('.gst-rate') ? tr.querySelector('.gst-rate').value : 0) || 0;
    var igstIn   = n(tr.querySelector('.igst'));
    var other    = n(tr.querySelector('.other-charges'));
    var roundOff = n(tr.querySelector('.round-off'));

    var qtyBase    = qtyPur * conv;
    var taxable    = qtyPur * rate;
    var discAmt    = (taxable * discPct) / 100;
    var netTaxable = taxable - discAmt;

    var cgst = 0, sgst = 0, igst = 0, totalGst = 0;
    if (igstIn > 0) {
        igst     = igstIn;
        totalGst = igstIn;
    } else {
        cgst     = (netTaxable * (gstRate / 2)) / 100;
        sgst     = (netTaxable * (gstRate / 2)) / 100;
        totalGst = cgst + sgst;
    }

    var totalAmt = netTaxable + totalGst + other + roundOff;
    var effCost  = qtyBase > 0 ? totalAmt / qtyBase : 0;

    var qtyBaseEl = tr.querySelector('.qty-base');
    if (qtyBaseEl) qtyBaseEl.value = qtyBase.toFixed(4);
    setVal(tr.querySelector('.taxable-val'), taxable);
    setVal(tr.querySelector('.disc-amt'), discAmt);
    setVal(tr.querySelector('.net-taxable'), netTaxable);
    setVal(tr.querySelector('.cgst'), cgst);
    setVal(tr.querySelector('.sgst'), sgst);
    setVal(tr.querySelector('.total-gst'), totalGst);
    setVal(tr.querySelector('.total-amt'), totalAmt);
    var ecEl = tr.querySelector('.eff-cost');
    if (ecEl) ecEl.value = effCost.toFixed(4);

    updateGrandTotal();
}

function updateGrandTotal() {
    var totTaxable = 0, totGst = 0, totOther = 0, totTotal = 0;
    document.querySelectorAll('#materialBody tr').forEach(function(tr) {
        totTaxable += n(tr.querySelector('.net-taxable'));
        totGst     += n(tr.querySelector('.total-gst'));
        totOther   += n(tr.querySelector('.other-charges'));
        totTotal   += n(tr.querySelector('.total-amt'));
    });
    document.getElementById('gt_taxable').textContent = '\u20B9' + totTaxable.toFixed(2);
    document.getElementById('gt_gst').textContent     = '\u20B9' + totGst.toFixed(2);
    document.getElementById('gt_other').textContent   = '\u20B9' + totOther.toFixed(2);
    document.getElementById('gt_total').textContent   = '\u20B9' + totTotal.toFixed(2);
}

function attachRowListeners(tr) {
    var calcFields = ['.qty-pur', '.conv-factor', '.rate', '.disc-pct', '.igst', '.other-charges', '.round-off'];
    calcFields.forEach(function(sel) {
        var el = tr.querySelector(sel);
        if (el) el.addEventListener('input', function() { calcRow(tr); });
    });
    var gstSel = tr.querySelector('.gst-rate');
    if (gstSel) gstSel.addEventListener('change', function() { calcRow(tr); });
}

function initFirstRow() {
    document.getElementById('materialBody').innerHTML = '';
    rowIndex = 0;
    addRow();
}

document.getElementById('addRow').addEventListener('click', addRow);

// Boot — run after DOM is ready
initFirstRow();
</script>

@endsection