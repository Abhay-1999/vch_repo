@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-3">

    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Kitchen Request Details</h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-3"><strong>Req No:</strong> {{ $hd->requstion_no }}</div>
                <div class="col-md-3"><strong>Date:</strong> {{ $hd->requstion_date }}</div>
                <div class="col-md-3"><strong>Rest Code:</strong> {{ $hd->rest_cd }}</div>
            </div>

            <form id="issueForm">
                @csrf

                <input type="hidden" name="hd_id" value="{{ $hd->trans_no }}">

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Requested Qty</th>
                            <th>Unit</th>
                            <th>Issue Qty</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($dt as $key => $d)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $d->item_desc }}</td>
                            <td>{{ $d->qty }}</td>
                            <td>{{ $d->unit_small_desc }}</td>

                            <td>
                                <input type="hidden" name="dt_id[]" value="{{ $d->trans_no }}">
                                <input type="hidden" name="item_code[]" value="{{ $d->item_code }}">
                                <input type="number" max="{{ $d->qty }}" step="0.01"
                                       class="form-control issue_qty"
                                       name="issue_qty[]">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="button" id="saveIssueBtn" class="btn btn-primary">Save & Issue</button>

            </form>

        </div>
    </div>

</div>
<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#saveIssueBtn').click(function() {

        let errors = [];

        $('.issue_qty').each(function(){
            let val = $(this).val();
            let max = $(this).attr('max');

            if(val === '' || parseFloat(val) < 0){
                errors.push('Issue qty cannot be empty or negative.');
            }
            if(parseFloat(val) > parseFloat(max)){
                errors.push('Issue qty cannot exceed requested qty.');
            }
        });

        if(errors.length > 0){
            Swal.fire("Error", errors.join("<br>"), "error");
            return;
        }

        let formData = new FormData(document.getElementById('issueForm'));

        $.ajax({
            url: "{{ route('store.request.issue.save') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success:function(res){
                if(res.success){
                    Swal.fire("Success", res.message, "success");
                    setTimeout(() => {
                        window.location.href = "{{ route('store.pending.request') }}";
                    }, 1200);
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            }
        });

    });
</script>

@endsection
