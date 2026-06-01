
@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <h4 class="mb-0">
                Reports Filter
            </h4>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('reports.day-end-sales.generate') }}">

                @csrf

                <div class="row">

                    {{-- REPORT SELECT --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Select Report
                        </label>

                        <select name="report_code"
                                id="report_code"
                                class="form-control"
                                required>

                            <option value="">
                                Select Report
                            </option>

                            {{-- SALES REPORTS --}}
                            <optgroup label="SALES REPORTS">

                                @foreach($reports->where('category', 'Sales') as $report)

                                    <option value="{{ $report->report_code }}">

                                        {{ $report->report_code }}
                                        -
                                        {{ $report->report_name }}

                                    </option>

                                @endforeach

                            </optgroup>

                            {{-- ITEM REPORTS --}}
                            <optgroup label="ITEM REPORTS">

                                @foreach($reports->where('category', 'Item') as $report)

                                    <option value="{{ $report->report_code }}">

                                        {{ $report->report_code }}
                                        -
                                        {{ $report->report_name }}

                                    </option>

                                @endforeach

                            </optgroup>

                            {{-- INVENTORY REPORTS --}}
                            <optgroup label="INVENTORY REPORTS">

                                @foreach($reports->where('category', 'Inventory') as $report)

                                    <option value="{{ $report->report_code }}">

                                        {{ $report->report_code }}
                                        -
                                        {{ $report->report_name }}

                                    </option>

                                @endforeach

                            </optgroup>

                            {{-- DISCOUNT REPORTS --}}
                            <optgroup label="DISCOUNT REPORTS">

                                @foreach($reports->where('category', 'Discount') as $report)

                                    <option value="{{ $report->report_code }}">

                                        {{ $report->report_code }}
                                        -
                                        {{ $report->report_name }}

                                    </option>

                                @endforeach

                            </optgroup>


                            {{-- CASH REPORTS --}}
                            <optgroup label="CASH REPORTS">

                                @foreach($reports->where('category', 'Cash') as $report)

                                    <option value="{{ $report->report_code }}">

                                        {{ $report->report_code }}
                                        -
                                        {{ $report->report_name }}

                                    </option>

                                @endforeach

                            </optgroup>

                        </select>

                    </div>

                    {{-- NORMAL BUSINESS DATE --}}
                    <div class="col-md-3 mb-3"
                         id="business_date_div">

                        <label class="form-label">
                            Business Date
                        </label>

                        <input type="date"
                               name="business_date"
                               class="form-control"
                               value="{{ date('Y-m-d') }}">

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RPT-013 FILTERS --}}
                {{-- ===================================================== --}}

                <div id="rpt013_filters"
                     style="display:none;">

                    <div class="row">

                        {{-- FROM DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                From Date
                            </label>

                            <input type="date"
                                   name="from_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">

                        </div>

                        {{-- TO DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                To Date
                            </label>

                            <input type="date"
                                   name="to_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">

                        </div>

                        {{-- CATEGORY --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Category
                            </label>

                            <select name="category[]"
                                    id="item_category"
                                    class="form-control"
                                    multiple>

                                @php

                                    $categories = DB::table('menu_items')
                                        ->select('category')
                                        ->whereNotNull('category')
                                        ->distinct()
                                        ->orderBy('category')
                                        ->get();

                                @endphp

                                @foreach($categories as $cat)

                                    <option value="{{ $cat->category }}">

                                        {{ $cat->category }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- TOP N --}}
                        <div class="col-md-2 mb-3">

                            <label class="form-label">
                                Top N
                            </label>

                            <input type="number"
                                   name="top_n"
                                   class="form-control"
                                   min="1"
                                   max="500"
                                   placeholder="Optional">

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RPT-023 FILTERS --}}
                {{-- ===================================================== --}}

                <div id="rpt023_filters"
                     style="display:none;">

                    <div class="row">

                        {{-- AS ON DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                As-on Date
                            </label>

                            <input type="date"
                                   name="as_on_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">

                        </div>

                        {{-- MATERIAL CATEGORY --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Material Category
                            </label>

                            <select name="stock_category[]"
                                    id="stock_category"
                                    class="form-control"
                                    multiple>

                                @php

                                    $stockCategories = DB::table('ingredient_masters')
                                        ->select('category')
                                        ->whereNotNull('category')
                                        ->distinct()
                                        ->orderBy('category')
                                        ->get();

                                @endphp

                                @foreach($stockCategories as $cat)

                                    <option value="{{ $cat->category }}">

                                        {{ $cat->category }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RPT-024 FILTERS --}}
                {{-- ===================================================== --}}

                <div id="rpt024_filters"
                     style="display:none;">

                    <div class="row">

                        {{-- MATERIAL --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Material
                            </label>

                            <select name="material_code"
                                    id="material_code"
                                    class="form-control">

                                <option value="">
                                    Select Material
                                </option>

                                @php

                                    $materials = DB::table('ingredient_masters')
                                        ->select(
                                            'ingredient_code',
                                            'ingredient_name'
                                        )
                                        ->orderBy('ingredient_name')
                                        ->get();

                                @endphp

                                @foreach($materials as $material)

                                    <option value="{{ $material->ingredient_code }}">

                                        {{ $material->ingredient_name }}
                                        -
                                        {{ $material->ingredient_code }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- FROM DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                From Date
                            </label>

                            <input type="date"
                                   name="ledger_from_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d', strtotime('-30 days')) }}">

                        </div>

                        {{-- TO DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                To Date
                            </label>

                            <input type="date"
                                   name="ledger_to_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">

                        </div>

                    </div>

                </div>

                {{-- ===================================================== --}}
                {{-- RPT-041 FILTERS --}}
                {{-- ===================================================== --}}

                <div id="rpt041_filters"
                     style="display:none;">

                    <div class="row">

                        {{-- FROM DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                From Date
                            </label>

                            <input type="date"
                                   name="discount_from_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">

                        </div>

                        {{-- TO DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                To Date
                            </label>

                            <input type="date"
                                   name="discount_to_date"
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">

                        </div>
                        {{-- DISCOUNT TYPE --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Discount Type
                            </label>

                            <select name="discount_type[]"
                                    id="discount_type"
                                    class="form-control"
                                    multiple>

                                @php

                                    $discountTypes = DB::table('discount_master')
                                        ->select('type')
                                        ->whereNotNull('type')
                                        ->where('type', '!=', '')
                                        ->distinct()
                                        ->orderBy('type')
                                        ->get();

                                @endphp

                                @foreach($discountTypes as $discount)

                                    <option value="{{ $discount->type }}">

                                        {{ $discount->type }}

                                    </option>

                                @endforeach

                            </select>

                        </div>
                    </div>

                </div>


                {{-- ===================================================== --}}
{{-- RPT-042 FILTERS --}}
{{-- ===================================================== --}}

<div id="rpt042_filters"
     style="display:none;">

    <div class="row">

        {{-- FROM DATE --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                From Date
            </label>

            <input type="date"
                   name="coupon_from_date"
                   class="form-control"
                   value="{{ date('Y-m-d', strtotime('-30 days')) }}">

        </div>

        {{-- TO DATE --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                To Date
            </label>

            <input type="date"
                   name="coupon_to_date"
                   class="form-control"
                   value="{{ date('Y-m-d') }}">

        </div>

        {{-- COUPON CODE --}}
        <div class="col-md-4 mb-3">

            <label class="form-label">
                Coupon Code
            </label>

            <select name="coupon_code[]"
                    id="coupon_code"
                    class="form-control"
                    multiple>

                @php

                    $coupons = DB::table('coupons')
                        ->select('coupon_code')
                        ->whereNotNull('coupon_code')
                        ->distinct()
                        ->orderBy('coupon_code')
                        ->get();

                @endphp

                @foreach($coupons as $coupon)

                    <option value="{{ $coupon->coupon_code }}">

                        {{ $coupon->coupon_code }}

                    </option>

                @endforeach

            </select>

        </div>

    </div>

</div>

{{-- ===================================================== --}}
{{-- RPT-015 FILTERS --}}
{{-- ===================================================== --}}

<div id="rpt015_filters"
     style="display:none;">

    <div class="row">

        {{-- FROM DATE --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                From Date
            </label>

            <input type="date"
                   name="from_date"
                   class="form-control"
                   value="{{ date('Y-m-d', strtotime('-7 days')) }}">

        </div>

        {{-- TO DATE --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                To Date
            </label>

            <input type="date"
                   name="to_date"
                   class="form-control"
                   value="{{ date('Y-m-d') }}">

        </div>

        {{-- TOP N --}}
        <div class="col-md-2 mb-3">

            <label class="form-label">
                Top N
            </label>

            <input type="number"
                   name="top_n"
                   class="form-control"
                   min="1"
                   max="100"
                   value="10">

        </div>

        {{-- RANK BY --}}
        <div class="col-md-3 mb-3">

            <label class="form-label">
                Rank By
            </label>

            <select name="rank_by"
                    class="form-control">

                <option value="Quantity">
                    Quantity
                </option>

                <option value="Revenue">
                    Revenue
                </option>

                <option value="Margin">
                    Margin
                </option>

            </select>

        </div>

    </div>

</div>
<div id="rpt004_filters"
     style="display:none;">

    <div class="row">

        <div class="col-md-3">

            <label>
                From Date
            </label>

            <input type="date"
                   name="from_date"
                   class="form-control"
                   value="{{ date('Y-m-d') }}">

        </div>

        <div class="col-md-3">

            <label>
                To Date
            </label>

            <input type="date"
                   name="to_date"
                   class="form-control"
                   value="{{ date('Y-m-d') }}">

        </div>

        <div class="col-md-3">

            <label>
                Type
            </label>

            <select name="order_type"
                    class="form-control">

                <option value="">
                    All
                </option>

                <option value="D">
                    Dine In
                </option>

                <option value="T">
                    Takeaway
                </option>

                <option value="O">
                    Online
                </option>

            </select>

        </div>

    </div>

</div>
<div id="rpt005_filters"
     style="display:none;">

    <div class="row">

        <div class="col-md-3">

            <label>
                From Date
            </label>

            <input type="date"
                   name="from_date"
                   class="form-control"
                   value="{{ date('Y-m-d') }}">

        </div>

        <div class="col-md-3">

            <label>
                To Date
            </label>

            <input type="date"
                   name="to_date"
                   class="form-control"
                   value="{{ date('Y-m-d') }}">

        </div>

    </div>

</div>
                {{-- BUTTON --}}
                <div class="row">

                    <div class="col-md-12 mt-2">

                        <button type="submit"
                                name="export"
                                value="view"
                                class="btn btn-primary">

                            Generate Report

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- SELECT2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet" />

{{-- JQUERY --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- SELECT2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    document
        .getElementById('report_code')
        .addEventListener('change', function () {

            let reportCode =
                this.value;

                let rpt004Filters =
    document.getElementById(
        'rpt004_filters'
    );

let rpt005Filters =
    document.getElementById(
        'rpt005_filters'
    );
                rpt004Filters.style.display = 'none';

            let rpt013Filters =
                document.getElementById('rpt013_filters');

            let rpt015Filters =
                document.getElementById('rpt015_filters');


            let rpt023Filters =
                document.getElementById('rpt023_filters');

            let rpt024Filters =
                document.getElementById('rpt024_filters');

            let rpt041Filters =
                document.getElementById('rpt041_filters');

            let rpt042Filters =
                document.getElementById('rpt042_filters');

            let businessDateDiv =
                document.getElementById('business_date_div');

            /*
            |--------------------------------------------------------------------------
            | HIDE ALL FIRST
            |--------------------------------------------------------------------------
            */

            

            rpt013Filters.style.display = 'none';
            rpt005Filters.style.display = 'none';
            rpt015Filters.style.display = 'none';

            rpt023Filters.style.display = 'none';

            rpt024Filters.style.display = 'none';

            rpt041Filters.style.display = 'none';

            rpt042Filters.style.display = 'none';

            businessDateDiv.style.display = 'none';

            /*
            |--------------------------------------------------------------------------
            | RPT-013 => ITEM WISE SALES
            |--------------------------------------------------------------------------
            */

            if (reportCode === 'RPT-013') {

                rpt013Filters.style.display = 'block';

            }else if (reportCode === 'RPT-0004') {

    rpt004Filters.style.display = 'block';

}else if (reportCode === 'RPT-0005') {

    rpt005Filters.style.display = 'block';

}


             if (reportCode === 'RPT-015') {

                rpt015Filters.style.display = 'block';

            }

            /*
            |--------------------------------------------------------------------------
            | RPT-023 => CURRENT STOCK
            |--------------------------------------------------------------------------
            */

            else if (reportCode === 'RPT-023') {

                rpt023Filters.style.display = 'block';

            }

            /*
            |--------------------------------------------------------------------------
            | RPT-024 => STOCK LEDGER
            |--------------------------------------------------------------------------
            */

            else if (reportCode === 'RPT-024') {

                rpt024Filters.style.display = 'block';

            }

            /*
            |--------------------------------------------------------------------------
            | RPT-041 => DISCOUNT SUMMARY
            |--------------------------------------------------------------------------
            */

            else if (reportCode === 'RPT-041') {

                rpt041Filters.style.display = 'block';

            }

            /*
            |--------------------------------------------------------------------------
            | RPT-042 => COUPON USAGE
            |--------------------------------------------------------------------------
            */

            else if (reportCode === 'RPT-042') {

                rpt042Filters.style.display = 'block';

            }

            /*
            |--------------------------------------------------------------------------
            | OTHER REPORTS
            |--------------------------------------------------------------------------
            */

            else {

                businessDateDiv.style.display = 'block';

            }

        });

    /*
    |--------------------------------------------------------------------------
    | SELECT2
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {

        $('#stock_category').select2({

            placeholder: 'Select Material Category',

            width: '100%'

        });

        $('#item_category').select2({

            placeholder: 'Select Category',

            width: '100%'

        });

        $('#material_code').select2({

            placeholder: 'Select Material',

            width: '100%'

        });

        $('#discount_type').select2({

            placeholder: 'Select Discount Type',

            width: '100%'

        });

        $('#coupon_code').select2({

            placeholder: 'Select Coupon Code',

            width: '100%'

        });

    });

</script>

@endsection
