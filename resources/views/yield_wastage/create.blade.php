@extends('auth.layouts.app')

@section('content')

<div class="container-fluid mt-4">

    {{-- Success Message --}}
    <div class="alert alert-success d-none" id="successMessage"></div>

    {{-- Error Message --}}
    <div class="alert alert-danger d-none" id="errorMessage"></div>

    <div class="card shadow-lg border-0">

        <div class="card-header bg-dark text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">
                    Yield & Wastage Entry
                </h4>

                <button type="button" class="btn btn-success btn-sm" id="addNewRow">
                    + Add Row
                </button>

            </div>

        </div>

        <div class="card-body">

            <form id="yieldForm">

                @csrf

                {{-- Header Section --}}
                <div class="row mb-4">

                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            Test Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="test_date"
                               class="form-control">

                        <small class="text-danger error_test_date"></small>
                    </div>

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Tested By <span class="text-danger">*</span>
                        </label>

                        <select name="tested_by" class="form-control">

                            <option value="">Select Chef</option>

                            <option value="Chef A">Chef A</option>
                            <option value="Chef B">Chef B</option>
                            <option value="Chef C">Chef C</option>
                            <option value="Dummy Chef">Dummy Chef</option>

                        </select>

                        <small class="text-danger error_tested_by"></small>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            Remarks
                        </label>

                        <input type="text"
                               name="remarks"
                               class="form-control"
                               placeholder="Enter remarks">

                    </div>

                </div>

                {{-- Table --}}
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle" id="itemTable">

                        <thead class="table-dark text-center">

                            <tr>
                                <th width="20%">Ingredient</th>
                                <th>AP Weight</th>
                                <th>Trim Loss</th>
                                <th>Cooking Loss</th>
                                <th>EP Weight</th>
                                <th>Yield %</th>
                                <th>AP Cost</th>
                                <th>AP Cost/GM</th>
                                <th>EP Cost/GM</th>
                                <th width="10%">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    <select name="ingredient_code[]"
                                            class="form-control ingredient_select">

                                        <option value="">
                                            Select Ingredient
                                        </option>

                                        @foreach($ingredients as $ingredient)

                                            <option value="{{ $ingredient->ingredient_code }}">
                                                {{ $ingredient->ingredient_name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="ap_weight[]"
                                           class="form-control ap_weight"
                                           placeholder="0.00">
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="trim_loss[]"
                                           class="form-control trim_loss"
                                           placeholder="0.00">
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="cooking_loss[]"
                                           class="form-control cooking_loss"
                                           placeholder="0.00">
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="ep_weight[]"
                                           class="form-control ep_weight bg-light"
                                           readonly>
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="yield_percent[]"
                                           class="form-control yield_percent bg-light"
                                           readonly>
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.01"
                                           name="ap_cost[]"
                                           class="form-control ap_cost"
                                           placeholder="0.00">
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.0001"
                                           name="ap_cost_gm[]"
                                           class="form-control ap_cost_gm bg-light"
                                           readonly>
                                </td>

                                <td>
                                    <input type="number"
                                           step="0.0001"
                                           name="ep_cost_gm[]"
                                           class="form-control ep_cost_gm bg-light"
                                           readonly>
                                </td>

                                <td class="text-center">

                                    <button type="button"
                                            class="btn btn-danger btn-sm removeRow">

                                        Remove

                                    </button>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                {{-- Submit --}}
                <div class="text-end mt-4">

                    <button type="submit"
                            class="btn btn-primary px-5"
                            id="saveBtn">

                        Save Entry

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function(){

    // ====================================
    // Add Row
    // ====================================

    $('#addNewRow').click(function(){

        let row = `
        <tr>

            <td>

                <select name="ingredient_code[]"
                        class="form-control ingredient_select">

                    <option value="">
                        Select Ingredient
                    </option>

                    @foreach($ingredients as $ingredient)

                        <option value="{{ $ingredient->ingredient_code }}">
                            {{ $ingredient->ingredient_name }}
                        </option>

                    @endforeach

                </select>

            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="ap_weight[]"
                       class="form-control ap_weight">
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="trim_loss[]"
                       class="form-control trim_loss">
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="cooking_loss[]"
                       class="form-control cooking_loss">
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="ep_weight[]"
                       class="form-control ep_weight bg-light"
                       readonly>
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="yield_percent[]"
                       class="form-control yield_percent bg-light"
                       readonly>
            </td>

            <td>
                <input type="number"
                       step="0.01"
                       name="ap_cost[]"
                       class="form-control ap_cost">
            </td>

            <td>
                <input type="number"
                       step="0.0001"
                       name="ap_cost_gm[]"
                       class="form-control ap_cost_gm bg-light"
                       readonly>
            </td>

            <td>
                <input type="number"
                       step="0.0001"
                       name="ep_cost_gm[]"
                       class="form-control ep_cost_gm bg-light"
                       readonly>
            </td>

            <td class="text-center">

                <button type="button"
                        class="btn btn-danger btn-sm removeRow">

                    Remove

                </button>

            </td>

        </tr>
        `;

        $('#itemTable tbody').append(row);

    });

    // ====================================
    // Remove Row
    // ====================================

    $(document).on('click','.removeRow',function(){

        let totalRows = $('#itemTable tbody tr').length;

        if(totalRows > 1)
        {
            $(this).closest('tr').remove();
        }
        else
        {
            alert('Minimum one row required');
        }

    });

    // ====================================
    // Auto Calculation
    // ====================================

    $(document).on(
        'keyup change',
        '.ap_weight,.trim_loss,.cooking_loss,.ap_cost',
        function(){

        let row = $(this).closest('tr');

        let apWeight    = parseFloat(row.find('.ap_weight').val()) || 0;
        let trimLoss    = parseFloat(row.find('.trim_loss').val()) || 0;
        let cookingLoss = parseFloat(row.find('.cooking_loss').val()) || 0;
        let apCost      = parseFloat(row.find('.ap_cost').val()) || 0;

        let epWeight = apWeight - (trimLoss + cookingLoss);

        if(epWeight < 0)
        {
            epWeight = 0;
        }

        let yieldPercent = 0;
        let apCostGm = 0;
        let epCostGm = 0;

        if(apWeight > 0)
        {
            yieldPercent = (epWeight / apWeight) * 100;
            apCostGm = apCost / apWeight;
        }

        if(epWeight > 0)
        {
            epCostGm = apCost / epWeight;
        }

        row.find('.ep_weight').val(epWeight.toFixed(2));
        row.find('.yield_percent').val(yieldPercent.toFixed(2));
        row.find('.ap_cost_gm').val(apCostGm.toFixed(4));
        row.find('.ep_cost_gm').val(epCostGm.toFixed(4));

    });

    // ====================================
    // AJAX SAVE
    // ====================================

    $('#yieldForm').submit(function(e){

        e.preventDefault();

        $('.text-danger').html('');
        $('#successMessage').addClass('d-none');
        $('#errorMessage').addClass('d-none');

        let formData = new FormData(this);

        $('#saveBtn').html('Saving...');
        $('#saveBtn').prop('disabled', true);

        $.ajax({

            url: "{{ route('yield.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(response){

                $('#saveBtn').html('Save Entry');
                $('#saveBtn').prop('disabled', false);

                $('#successMessage')
                    .removeClass('d-none')
                    .html(response.message);

                $('#yieldForm')[0].reset();

                $('#itemTable tbody').html(`
                    <tr>

                        <td>

                            <select name="ingredient_code[]"
                                    class="form-control ingredient_select">

                                <option value="">
                                    Select Ingredient
                                </option>

                                @foreach($ingredients as $ingredient)

                                    <option value="{{ $ingredient->ingredient_code }}">
                                        {{ $ingredient->ingredient_name }}
                                    </option>

                                @endforeach

                            </select>

                        </td>

                        <td>
                            <input type="number" step="0.01"
                                   name="ap_weight[]"
                                   class="form-control ap_weight">
                        </td>

                        <td>
                            <input type="number" step="0.01"
                                   name="trim_loss[]"
                                   class="form-control trim_loss">
                        </td>

                        <td>
                            <input type="number" step="0.01"
                                   name="cooking_loss[]"
                                   class="form-control cooking_loss">
                        </td>

                        <td>
                            <input type="number" step="0.01"
                                   name="ep_weight[]"
                                   class="form-control ep_weight bg-light"
                                   readonly>
                        </td>

                        <td>
                            <input type="number" step="0.01"
                                   name="yield_percent[]"
                                   class="form-control yield_percent bg-light"
                                   readonly>
                        </td>

                        <td>
                            <input type="number" step="0.01"
                                   name="ap_cost[]"
                                   class="form-control ap_cost">
                        </td>

                        <td>
                            <input type="number" step="0.0001"
                                   name="ap_cost_gm[]"
                                   class="form-control ap_cost_gm bg-light"
                                   readonly>
                        </td>

                        <td>
                            <input type="number" step="0.0001"
                                   name="ep_cost_gm[]"
                                   class="form-control ep_cost_gm bg-light"
                                   readonly>
                        </td>

                        <td class="text-center">

                            <button type="button"
                                    class="btn btn-danger btn-sm removeRow">

                                Remove

                            </button>

                        </td>

                    </tr>
                `);

            },

            error: function(xhr){

                $('#saveBtn').html('Save Entry');
                $('#saveBtn').prop('disabled', false);

                if(xhr.status == 422)
                {
                    $.each(xhr.responseJSON.errors,function(key,value){

                        $('.error_'+key).html(value[0]);

                    });
                }
                else
                {
                    $('#errorMessage')
                        .removeClass('d-none')
                        .html('Something went wrong');
                }

            }

        });

    });

});

</script>

@endpush