@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">
            <h4>Recipe Costing</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('recipe-cost.store') }}"
                  method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-3">
                        <label>Item Code</label>

                        <input type="text"
                               name="item_code"
                               class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Item Name</label>

                        <input type="text"
                               name="item_name"
                               class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Category</label>

                        <input type="text"
                               name="category"
                               class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Servings</label>

                        <input type="number"
                               step="0.01"
                               name="servings"
                               class="form-control">
                    </div>

                </div>

                <hr>

                <table class="table table-bordered">

                    <thead class="table-primary">

                        <tr>

                            <th>Type</th>
                            <th>Component</th>
                            <th width="120">Qty</th>
                            <th width="120">Rate</th>
                            <th width="120">Cost</th>
                            <th width="80">Action</th>

                        </tr>

                    </thead>

                    <tbody id="recipeTable">

                        <tr>

                            <td>

                                <select name="component_type[]"
                                        class="form-control type">

                                    <option value="INGREDIENT">
                                        INGREDIENT
                                    </option>

                                    <option value="SUB_RECIPE">
                                        SUB RECIPE
                                    </option>

                                </select>

                            </td>

                            <td>

                                <select name="component_id[]"
                                        class="form-control component">

                                    <option value="">Select</option>

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
                <hr>

<div class="row">

    <div class="col-md-2">
        <label>Total Recipe Cost (Rs)</label>
        <input type="text" name="total_recipe_cost" id="total_recipe_cost" class="form-control" readonly>
    </div>

    <div class="col-md-2">
        <label>Cost per Serving (Rs)</label>
        <input type="text" name="cost_per_serving" id="cost_per_serving" class="form-control" readonly>
    </div>

    <div class="col-md-2">
        <label>Garnish & Plating (Rs)</label>
        <input type="number" name="garnish_cost" id="garnish_cost" class="form-control">
    </div>

    <div class="col-md-2">
        <label>Packaging Cost (Rs)</label>
        <input type="number" name="packaging_cost" id="packaging_cost" class="form-control">
    </div>

    <div class="col-md-2">
        <label>Overhead %</label>
        <input type="number" name="overhead_percent" id="overhead_percent" class="form-control">
    </div>

    <div class="col-md-2">
        <label>Plate Cost (Rs)</label>
        <input type="text" name="plate_cost" id="plate_cost" class="form-control" readonly>
    </div>

</div>

                <button type="button"
                        id="addRow"
                        class="btn btn-success">

                    Add Row

                </button>

                <button type="submit"
                        class="btn btn-primary">

                    Save Recipe

                </button>

            </form>

        </div>

    </div>

</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

let ingredients = @json($ingredients);
let subRecipes = @json($subRecipes);

/* =========================
   LOAD COMPONENTS
========================= */
function loadComponents(row, type){

    let select = row.find('.component');
    select.html('<option value="">Select</option>');

    if(type === 'INGREDIENT'){

        ingredients.forEach(function(item){
            select.append(`
                <option value="${item.id}" data-rate="${item.costing_rate}">
                    ${item.ingredient_name}
                </option>
            `);
        });

    } else {

        subRecipes.forEach(function(item){
            select.append(`
                <option value="${item.id}" data-rate="${item.cost_per_gram}">
                    ${item.sub_recipe_name}
                </option>
            `);
        });

    }
}

/* =========================
   READY FUNCTION
========================= */
$(document).ready(function(){

    loadComponents($('#recipeTable tr:first'), 'INGREDIENT');

    /* ADD ROW */
    $('#addRow').click(function(){

        let row = $('#recipeTable tr:first').clone();

        row.find('input').val('');
        row.find('select').val('');

        $('#recipeTable').append(row);

        loadComponents(row, 'INGREDIENT');

        calculateTotal();

    });

    /* TYPE CHANGE */
    $(document).on('change', '.type', function(){

        let row = $(this).closest('tr');
        loadComponents(row, $(this).val());

    });

    /* COMPONENT CHANGE */
    $(document).on('change', '.component', function(){

        let rate = parseFloat($(this).find(':selected').data('rate')) || 0;
        let row = $(this).closest('tr');

        row.find('.rate').val(rate);

        calculateRow(row);
        calculateTotal();

    });

    /* QTY CHANGE */
    $(document).on('keyup change', '.qty', function(){

        let row = $(this).closest('tr');

        calculateRow(row);
        calculateTotal();

    });

    /* REMOVE ROW */
    $(document).on('click', '.removeRow', function(){

        $(this).closest('tr').remove();

        calculateTotal();

    });

    /* OTHER COST INPUT CHANGE */
    $(document).on('keyup change', 
        '#garnish_cost,#packaging_cost,#overhead_percent,input[name="servings"]',
        function(){
            calculateTotal();
        }
    );

});

/* =========================
   ROW CALCULATION
========================= */
function calculateRow(row){

    let qty = parseFloat(row.find('.qty').val()) || 0;
    let rate = parseFloat(row.find('.rate').val()) || 0;

    let cost = qty * rate;

    row.find('.cost').val(cost.toFixed(2));

}

/* =========================
   TOTAL CALCULATION
========================= */
function calculateTotal(){

    let total = 0;

    $('.cost').each(function(){
        total += parseFloat($(this).val()) || 0;
    });

    $('#total_recipe_cost').val(total.toFixed(2));

    let servings = parseFloat($('input[name="servings"]').val()) || 1;

    let perServing = total / servings;
    $('#cost_per_serving').val(perServing.toFixed(2));

    let garnish = parseFloat($('#garnish_cost').val()) || 0;
    let packaging = parseFloat($('#packaging_cost').val()) || 0;
    let overheadPercent = parseFloat($('#overhead_percent').val()) || 0;

    let overhead = (total * overheadPercent) / 100;

    let plateCost = perServing + garnish + packaging + overhead;

    $('#plate_cost').val(plateCost.toFixed(2));

}

</script>

@endsection