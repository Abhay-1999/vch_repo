@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Create Sub Recipe</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('sub-recipes.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Sub Recipe Code</label>

                        <input type="text"
                               name="sub_recipe_code"
                               class="form-control"
                               value="{{ $sub_code }}"
                               readonly>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Sub Recipe Name</label>

                        <input type="text"
                               name="sub_recipe_name"
                               class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Batch Output</label>

                        <input type="number"
                               step="0.01"
                               name="batch_output"
                               class="form-control">

                    </div>

                </div>

                <hr>

                <table class="table table-bordered">

                    <thead class="table-dark">

                        <tr>

                            <th>Ingredient</th>

                            <th width="150">Qty</th>

                            <th width="150">Base UOM</th>

                            <th width="150">Rate</th>

                            <th width="150">Cost</th>

                            <th width="80">Action</th>

                        </tr>

                    </thead>

                    <tbody id="ingredientTable">

                        <tr>

                            <td>

                                <select name="ingredient_id[]"
                                        class="form-control ingredient">

                                    <option value="">Select</option>

                                    @foreach($ingredients as $ingredient)

                                        <option
                                            value="{{ $ingredient->id }}"
                                            data-rate="{{ $ingredient->costing_rate }}"
                                            data-base_uom="{{ $ingredient->base_uom }}">

                                            {{ $ingredient->ingredient_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </td>

                            <td>

                                <input type="number"
                                       step="0.01"
                                       name="qty[]"
                                       class="form-control qty">

                            </td>

                            <td>

                                <input type="text"
                                       name="base_uom[]"
                                       class="form-control base_uom"
                                       readonly>

                            </td>

                            <td>

                                <input type="text"
                                       class="form-control rate"
                                       readonly>

                            </td>

                            <td>

                                <input type="text"
                                       class="form-control cost"
                                       readonly>

                            </td>

                            <td>

                                <button type="button"
                                        class="btn btn-danger removeRow">

                                    X

                                </button>

                            </td>

                        </tr>

                    </tbody>

                </table>

                <button type="button"
                        id="addRow"
                        class="btn btn-success">

                    Add Row

                </button>

                <button type="submit"
                        class="btn btn-primary">

                    Save Sub Recipe

                </button>

            </form>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

$(document).ready(function(){

    // =========================
    // ADD NEW ROW
    // =========================

    $('#addRow').click(function(){

        let row = `

        <tr>

            <td>

                <select name="ingredient_id[]"
                        class="form-control ingredient">

                    <option value="">Select</option>

                    @foreach($ingredients as $ingredient)

                        <option
                            value="{{ $ingredient->id }}"
                            data-rate="{{ $ingredient->costing_rate }}"
                            data-base_uom="{{ $ingredient->base_uom }}">

                            {{ $ingredient->ingredient_name }}

                        </option>

                    @endforeach

                </select>

            </td>

            <td>

                <input type="number"
                       step="0.01"
                       name="qty[]"
                       class="form-control qty">

            </td>

            <td>

                <input type="text"
                       name="base_uom[]"
                       class="form-control base_uom"
                       readonly>

            </td>

            <td>

                <input type="text"
                       class="form-control rate"
                       readonly>

            </td>

            <td>

                <input type="text"
                       class="form-control cost"
                       readonly>

            </td>

            <td>

                <button type="button"
                        class="btn btn-danger removeRow">

                    X

                </button>

            </td>

        </tr>

        `;

        $('#ingredientTable').append(row);

    });

    // =========================
    // INGREDIENT CHANGE
    // =========================

    $(document).on('change','.ingredient',function(){

        let selected = $(this).find(':selected');

        let rate = selected.data('rate');

        let base_uom = selected.data('base_uom');

        let row = $(this).closest('tr');

        row.find('.rate').val(rate);

        row.find('.base_uom').val(base_uom);

        calculate(row);

    });

    // =========================
    // QTY CHANGE
    // =========================

    $(document).on('keyup change','.qty',function(){

        let row = $(this).closest('tr');

        calculate(row);

    });

    // =========================
    // CALCULATE COST
    // =========================

    function calculate(row){

        let qty = parseFloat(row.find('.qty').val()) || 0;

        let rate = parseFloat(row.find('.rate').val()) || 0;

        let cost = qty * rate;

        row.find('.cost').val(cost.toFixed(2));

    }

    // =========================
    // REMOVE ROW
    // =========================

    $(document).on('click','.removeRow',function(){

        $(this).closest('tr').remove();

    });

});

</script>

@endsection