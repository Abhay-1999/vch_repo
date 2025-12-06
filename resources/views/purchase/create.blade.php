@extends('auth.layouts.app')

@section('content')
<div class="container">

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">New Purchase Entry</h5>
        </div>

        <div class="card-body">

        <form id="purchaseForm" action="{{ route('purchase.store') }}" method="POST">
                @csrf

                <!-- Top Section -->
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Rest Code *</label>
                        <input type="text" name="rest_cd" class="form-control" value="01" readonly required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Trans No</label>
                        <input type="text" name="trans_no" class="form-control" 
                        value="{{ $nextTransNo }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Trans Date</label>
                        <input type="date" name="trans_date" class="form-control"
                        value="{{ date('Y-m-d') }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Supplier </label>
                        <select name="supp_cd" class="form-control">
                                        <option>Select</option>
                                    @foreach($supplier_masters as $supplier_master)
                                        <option value="{{ $supplier_master->supp_cd  }}">{{ $supplier_master->supp_name }}</option>
                                    @endforeach
                                </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Supplier Bill No</label>
                        <input type="text" name="supp_billno" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Supplier Bill Date</label>
                        <input type="date" name="supp_billdt" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Purchase Order No</label>
                        <input type="text" name="porder_no" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Purchase Order Date</label>
                        <input type="date" name="porder_date" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Delivery Challan</label>
                        <input type="text" name="delivery_challan" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control">
                    </div>
                </div>

                <hr>

                <h5>Item Details</h5>

                <!-- Item Table -->
                <table class="table table-bordered" id="itemTable">
                    <thead class="bg-light">
                        <tr>
                            <th width="18%">Item</th>
                            <th width="10%">Qty</th>
                            <th width="10%">Unit</th>
                            <th width="10%">Rate</th>
                            <th width="10%">GST %</th>
                            <th width="10%">SGST Amt</th>
                            <th width="10%">CGST Amt</th>
                            <th width="10%">Total</th>
                            <th width="5%">#</th>
                        </tr>
                    </thead>

                    <tbody id="itemBody">
                        <tr>
                            <td>
                                <select name="item_code[]" class="form-control">
                                <option>Select</option>

                                    @foreach($items as $item)
                                        <option value="{{ $item->item_code }}">{{ $item->item_desc }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td><input type="number" class="form-control qty" step="0.001" name="qty[]"></td>
                            <td>
                            <select name="unit_cd[]" class="form-control">
                                <option>Select</option>

                                    @foreach($unit_masters as $unit_master)
                                        <option value="{{ $unit_master->unit_cd }}">{{ $unit_master->unit_small_desc }}</option>
                                    @endforeach
                                </select>
</td>
                            <td><input type="number" class="form-control rate" step="0.01" name="rate[]"></td>

                            <td><input type="number" class="form-control sgst_per" step="0.01" name="sgst_per[]"></td>
                            <!-- <td><input type="number" class="form-control cgst_per" step="0.01" name="cgst_per[]"></td> -->

                            <td><input type="number" class="form-control sgst_amt" step="0.01" name="sgst_amt[]" readonly></td>
                            <td><input type="number" class="form-control cgst_amt" step="0.01" name="cgst_amt[]" readonly></td>

                            <td><input type="number" class="form-control total" step="0.01" name="total[]" readonly></td>

                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-success mb-3" id="addRow">+ Add Item</button>

                <hr>

                <!-- Final Amount Section -->
                <div class="row">

                    <div class="col-md-4">
                        <label>Gross Value</label>
                        <input type="number" name="gross_val" id="gross_val" class="form-control" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Round Off</label>
                        <input type="number" name="r_off" id="r_off" class="form-control" step="0.01">
                    </div>

                    <div class="col-md-4">
                        <label>Final Invoice Value</label>
                        <input type="number" name="invoice_val" id="invoice_val" class="form-control" readonly>
                    </div>
                </div>

                <button class="btn btn-primary mt-4">Save Entry</button>

            </form>

        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$("#purchaseForm").submit(function(e) {
    e.preventDefault();
    document.getElementById('loader-overlay').style.display = 'flex';

    let formData = new FormData(this);

    $.ajax({
        url: "{{ route('purchase.store') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,

        beforeSend: function () {
            $(".text-danger").remove();
        },

        success: function(res) {

            if(res.success === true){
                document.getElementById('loader-overlay').style.display = 'none';

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Purchase Entry Saved Successfully!",
                });

                $("#purchaseForm")[0].reset();
            }

            if(res.success === false){
                document.getElementById('loader-overlay').style.display = 'none';

                showErrors(res.errors);
            }
        },

        error: function(xhr){
            console.log(xhr.responseText);
        }
    });
});

function showErrors(errors){
    
    $.each(errors, function(key, value){
        let input = $("[name='"+ key +"']");

        if(input.length){
            input.after("<span class='text-danger'>"+ value[0] +"</span>");
        }
    });
}

function calculate() {
    let gross = 0;

    $("#itemBody tr").each(function () {

        let qty = parseFloat($(this).find(".qty").val()) || 0;
        let rate = parseFloat($(this).find(".rate").val()) || 0;

        let sgst_per = parseFloat($(this).find(".sgst_per").val()) || 0;

        let gst_per = sgst_per/2;

        let amount = qty * rate;

        let sgst_amt = (amount * gst_per) / 100;

        let total = amount + sgst_amt + sgst_amt;

        $(this).find(".sgst_amt").val(sgst_amt.toFixed(2));
        $(this).find(".cgst_amt").val(sgst_amt.toFixed(2));
        $(this).find(".total").val(total.toFixed(2));

        gross += total;
    });

    $("#gross_val").val(gross.toFixed(2));

    let roff = parseFloat($("#r_off").val()) || 0;

    $("#invoice_val").val((gross + roff).toFixed(2));
}

// Recalculate on changes
$(document).on("keyup change", ".qty, .rate, .sgst_per, .cgst_per, #r_off", function () {
    calculate();
});

// Remove Row
$(document).on("click", ".remove", function () {
    $(this).closest("tr").remove();
    calculate();
});

// Add Row
$("#addRow").click(function () {
    let row = `<tr>
        <td>
            <select name="item_code[]" class="form-control">
            <option>Select</option>

                @foreach($items as $item)
                    <option value="{{ $item->item_code }}">{{ $item->item_desc }}</option>
                @endforeach
            </select>
        </td>

        <td><input type="number" class="form-control qty" step="0.001" name="qty[]"></td>
        <td>
                            <select name="unit_cd[]" class="form-control">
                                <option>Select</option>

                                    @foreach($unit_masters as $unit_master)
                                        <option value="{{ $unit_master->unit_cd }}">{{ $unit_master->unit_small_desc }}</option>
                                    @endforeach
                                </select>
</td>
        <td><input type="number" class="form-control rate" step="0.01" name="rate[]"></td>

        <td><input type="number" class="form-control sgst_per" step="0.01" name="sgst_per[]"></td>

        <td><input type="number" class="form-control sgst_amt" step="0.01" name="sgst_amt[]" readonly></td>
        <td><input type="number" class="form-control cgst_amt" step="0.01" name="cgst_amt[]" readonly></td>

        <td><input type="number" class="form-control total" step="0.01" name="total[]" readonly></td>

        <td><button type="button" class="btn btn-danger btn-sm remove">X</button></td>
    </tr>`;

    $("#itemBody").append(row);
});

</script>
@endsection
