@extends('auth.layouts.app')

@section('content')
<div class="container">

<form method="POST" action="{{ url('admin/purchase/store') }}">
@csrf

<div class="row">

<div class="col-md-4">
<label>Supplier</label>
<select name="supplier_id" class="form-control">
@foreach($suppliers as $supplier)
<option value="{{ $supplier->id }}">
{{ $supplier->supplier_name }}
</option>
@endforeach
</select>
</div>

</div>

<table class="table table-bordered" id="purchaseTable">

    <thead class="table-dark">
        <tr>
            <th width="30%">Material</th>
            <th width="15%">Qty</th>
            <th width="15%">Rate</th>
            <th width="20%">Amount</th>
            <th width="10%">
                <button type="button"
                        onclick="addRow()"
                        class="btn btn-success btn-sm">
                    +
                </button>
            </th>
        </tr>
    </thead>

    <tbody>

        <tr>

            <td>
                <select name="material_id[]" class="form-control material_id">

                    @foreach($materials as $material)

                    <option value="{{ $material->id }}">
                        {{ $material->material_name }}
                    </option>

                    @endforeach

                </select>
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="qty[]"
                       class="form-control qty"
                       value="0">
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="rate[]"
                       class="form-control rate"
                       value="0">
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="amount[]"
                       class="form-control amount"
                       readonly
                       value="0">
            </td>

            <td>
                <button type="button"
                        class="btn btn-danger btn-sm removeRow">
                    X
                </button>
            </td>

        </tr>

    </tbody>

    <tfoot>

        <tr>
            <th colspan="3" class="text-end">
                Grand Total
            </th>

            <th>
                <input type="text"
                       id="grand_total_show"
                       class="form-control"
                       readonly
                       value="0">
            </th>

            <th></th>
        </tr>

    </tfoot>

</table>

<input type="hidden" name="grand_total" id="grand_total">

<button class="btn btn-primary">
    Save Purchase
</button>


</form>
</div>
<!-- JQUERY -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- BOOTSTRAP JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

function addRow()
{
    var row = `
    <tr>

        <td>
            <select name="material_id[]" class="form-control material_id">

                @foreach($materials as $material)

                <option value="{{ $material->id }}">
                    {{ $material->material_name }}
                </option>

                @endforeach

            </select>
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="qty[]"
                   class="form-control qty"
                   value="0">
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="rate[]"
                   class="form-control rate"
                   value="0">
        </td>

        <td>
            <input type="number"
                   step="0.01"
                   name="amount[]"
                   class="form-control amount"
                   readonly
                   value="0">
        </td>

        <td>
            <button type="button"
                    class="btn btn-danger btn-sm removeRow">
                X
            </button>
        </td>

    </tr>
    `;

    $('#purchaseTable tbody').append(row);
}

$(document).on('keyup change','.qty,.rate',function(){

    var tr = $(this).closest('tr');

    var qty = parseFloat(tr.find('.qty').val()) || 0;

    var rate = parseFloat(tr.find('.rate').val()) || 0;

    var amount = qty * rate;

    tr.find('.amount').val(amount.toFixed(2));

    calculateTotal();
});

function calculateTotal()
{
    var total = 0;

    $('.amount').each(function(){

        total += parseFloat($(this).val()) || 0;
    });

    $('#grand_total_show').val(total.toFixed(2));

    $('#grand_total').val(total.toFixed(2));
}

$(document).on('click','.removeRow',function(){

    if($('#purchaseTable tbody tr').length > 1)
    {
        $(this).closest('tr').remove();

        calculateTotal();
    }
});

</script>
@endsection
