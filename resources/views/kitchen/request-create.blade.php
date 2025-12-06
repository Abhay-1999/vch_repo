@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Kitchen Item Request</h4>
        </div>

        <div class="card-body">

            <!-- ALERT AREA -->
            <div id="alert_area"></div>

            <form id="requestForm">
                @csrf

                <!-- HEADER FORM -->
                <div class="row g-3">

                    <div class="col-md-2">
                        <label class="form-label">Rest Code *</label>
                        <input type="text" class="form-control" name="rest_cd" id="rest_cd" value="01" readonly>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Trans No *</label>
                        <input type="text" class="form-control" name="trans_no" id="trans_no"
                            value="{{ $trans_no }}" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Trans Date *</label>
                        <input type="date" class="form-control" name="trans_date" id="trans_date"
                            value="{{ date('Y-m-d') }}" readonly>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Requisition No *</label>
                        <input type="text" class="form-control" name="requstion_no" id="requstion_no">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Requisition Date *</label>
                        <input type="date" class="form-control" name="requstion_date" id="requstion_date">
                    </div>

                </div>

                <hr class="mt-4">

                <!-- ITEM DETAILS CARD -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Item Details</h5>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-bordered mb-0" id="itemTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Item *</th>
                                    <th width="15%">Qty *</th>
                                    <th width="15%">Unit *</th>
                                    <th width="30%">Remark</th>
                                    <th width="5%">#</th>
                                </tr>
                            </thead>

                            <tbody id="itemBody">
                                <tr>
                                    <td>
                                        <select name="item_code[]" class="form-control item_code">
                                            <option value="">Select</option>
                                            @foreach($items as $i)
                                                <option value="{{ $i->item_code }}">{{ $i->item_desc }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="qty[]" class="form-control qty">
                                    </td>
                                    <td>
                                        <select name="unit_cd[]" class="form-control unit_cd">
                                            @foreach($unit_masters as $unit_master)
                                                <option value="{{ $unit_master->unit_cd }}">{{ $unit_master->unit_small_desc }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="remark[]" class="form-control">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="mt-3 d-flex justify-content-between">

                    <button type="button" id="addRow" class="btn btn-success">
                        + Add Item
                    </button>

                    <button type="button" id="saveBtn" class="btn btn-primary">
                        Submit Request
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>


<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// ADD ROW
$('#addRow').click(function(){
    $('#itemBody').append(`
        <tr>
            <td>
                <select name="item_code[]" class="form-control item_code">
                    <option value="">Select</option>
                    @foreach($items as $i)
                        <option value="{{ $i->item_code }}">{{ $i->item_desc }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" step="0.01" name="qty[]" class="form-control qty"></td>
            <td>
                <select name="unit_cd[]" class="form-control unit_cd">
                    @foreach($unit_masters as $unit_master)
                        <option value="{{ $unit_master->unit_cd }}">{{ $unit_master->unit_small_desc }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="remark[]" class="form-control"></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
        </tr>
    `);
});

// REMOVE ROW
$(document).on('click', '.removeRow', function(){
    $(this).closest('tr').remove();
});

// AJAX SAVE
$('#saveBtn').click(function(){

    let errors = [];

    if($('#rest_cd').val() === '') errors.push('Rest Code is required');
    if($('#trans_date').val() === '') errors.push('Transaction date is required');
    if($('#requstion_no').val() === '') errors.push('Requisition number is required');
    if($('#requstion_date').val() === '') errors.push('Requisition date is required');

    let anyItem = false;

    $('#itemTable tbody tr').each(function(){
        let item = $(this).find('.item_code').val();
        let qty = $(this).find('.qty').val();
        let unit = $(this).find('.unit_cd').val();
        if(item !== '' && qty !== '' && unit !== ''){
            anyItem = true;
        }
    });

    if(!anyItem){
        errors.push('At least one item row is required');
    }

    if(errors.length > 0){
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: errors.join("<br>"),
        });
        return;
    }

    let formData = new FormData(document.getElementById('requestForm'));

    $.ajax({
        url: "{{ route('kitchen.request.save') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            if(res.success){
                Swal.fire({
                    icon: 'success',
                    title: "Success",
                    text: res.message,
                });
                $('#requestForm')[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: res.message,
                });
            }
        },
        error: function(){
            Swal.fire({
                icon: 'error',
                title: "Server Error",
                text: "Something went wrong",
            });
        }
    });

});

</script>

@endsection
