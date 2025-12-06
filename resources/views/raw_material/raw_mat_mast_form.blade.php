@extends('auth.layouts.app')
@section('content')
<style>
    label{
        text-transform:uppercase;
        color:brown;
        font-weight:bold;
    }
</style>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Raw Material Master Form</h5>
                </div>
                <div class="card-body mt-3">
                    <form id="rawMatForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Item Description</label>
                                <input type="text" class="form-control" name="item_desc">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="text" class="form-control" name="qty" id="qty">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Unit Code</label>
                                <select name="unit_cd" id="unit_cd" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($unitMast as $unit)
                                        <option value="{{ $unit->unit_cd }}">{{ $unit->unit_desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category Code</label>
                                <select name="catg_cd" id="catg_cd" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($catMast as $cat)
                                        <option value="{{ $cat->catg_cd }}">{{ $cat->catg_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier Code</label>
                                <input type="text" class="form-control" name="supp_code">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier Bill No</label>
                                <input type="text" class="form-control" name="supp_billno">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Supplier Bill Date</label>
                                <input type="date" class="form-control" name="supp_billdt">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Remark</label>
                                <input type="text" class="form-control" name="remark">
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <button type="submit" class="btn btn-primary btn-md" id="saveBtn" >
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        const requiredFields = $('.required-field');
        const saveBtn = $('#saveBtn');

        function checkForm() {
            let valid = true;
            requiredFields.each(function() {
                if ($(this).val().trim() === '') {
                    valid = false;
                }
            });
        }

        requiredFields.on('input change', checkForm);

        // Quantity validation - only numbers & 3 decimals
        $('#qty').on('input', function() {
            let pattern = /^\d*\.?\d{0,3}$/;
            if (!pattern.test(this.value)) {
                this.value = this.value.slice(0, -1);
            }
        });

        // AJAX Submit
        $('#rawMatForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("raw_mat_store") }}',
                type: 'POST',
                data: $(this).serialize(),

                success: function(response) {

                    $('.is-invalid').removeClass('is-invalid');
                    showAlert('success', response.message);
                    $('#rawMatForm')[0].reset();
                    saveBtn.prop('disabled', true);
                    setTimeout(() => {
                        saveBtn.prop('disabled', false);
                    }, 3000);

                    $('#loader-overlay').hide();
                },

                error: function(xhr) {
                    $('.is-invalid').removeClass('is-invalid');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let msg = "<ul style='margin:0;padding-left:20px;'>";

                        $.each(errors, function(field, messages) {
                            $(`[name="${field}"]`).addClass('is-invalid');
                            msg += "<li>" + messages[0] + "</li>";
                        });

                        msg += "</ul>";
                        showAlert('error', msg);
                    } 
                    else {
                        showAlert('error', 'Something went wrong.');
                    }
                    $('#loader-overlay').hide(); // ⭐ IMPORTANT: hide loader on error
                }
            });
        });

        // Alert function
        function showAlert(type, message) {
            let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            let alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            $('.card-body').prepend(alertHtml);

            setTimeout(() => {
                $('.alert').fadeOut();
            }, 3000);
        }
    });
</script>

@endsection