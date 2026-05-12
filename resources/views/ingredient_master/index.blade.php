@extends('auth.layouts.app')

@section('content')

<style>

.custom-scroll{
    overflow-x: auto;
    white-space: nowrap;
}

.custom-scroll::-webkit-scrollbar{
    height: 10px;
}

.custom-scroll::-webkit-scrollbar-thumb{
    background: #888;
    border-radius: 10px;
}

.custom-scroll::-webkit-scrollbar-thumb:hover{
    background: #555;
}

table th{
    white-space: nowrap;
    vertical-align: middle;
}

table td{
    vertical-align: top;
}

.inner-table th{
    font-size: 12px;
    color: #555;
    padding: 2px 5px;
    white-space: nowrap;
}

.inner-table td{
    font-size: 13px;
    padding: 2px 5px;
}

</style>

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="fw-bold">
            <i class="fa fa-cubes me-2"></i>
            Ingredient Master List
        </h3>

        <a href="{{ route('ingredient.create') }}"
           class="btn btn-success">

            <i class="fa fa-plus"></i>
            Add Ingredient

        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- Card --}}
    <div class="card shadow border-0">

        <div class="card-body p-0">

            {{-- Scroll Wrapper --}}
            <div class="table-responsive custom-scroll">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-dark text-center">

                        <tr>

                            <th>#</th>

                            <th>Code</th>

                            <th>Ingredient</th>

                            <th>Category</th>

                            {{-- Purchase Details --}}
                            <th width="260">
                                Purchase Details
                            </th>

                            {{-- Costing Details --}}
                            <th width="320">
                                Costing Details
                            </th>

                            <th>Supplier</th>

                            <th>Updated</th>

                        

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ingredients as $key => $item)

                            <tr>

                                {{-- Serial --}}
                                <td class="text-center">
                                    {{ $key + 1 }}
                                </td>

                                {{-- Code --}}
                                <td>

                                    <span class="badge bg-primary">
                                        {{ $item->ingredient_code }}
                                    </span>

                                </td>

                                {{-- Ingredient --}}
                                <td>

                                    <strong>
                                        {{ $item->ingredient_name }}
                                    </strong>

                                </td>

                                {{-- Category --}}
                                <td>
                                    {{ $item->category ?? '-' }}
                                </td>

                                {{-- Purchase Details --}}
                                <td>

                                    <table class="table table-sm table-borderless inner-table mb-0">

                                        <tr>
                                            <th width="50%">
                                                Purchase UOM
                                            </th>

                                            <td>
                                                {{ strtoupper($item->purchase_uom ?? '-') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Purchase Qty
                                            </th>

                                            <td>
                                                {{ number_format($item->purchase_qty, 3) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Purchase Cost
                                            </th>

                                            <td>
                                                ₹ {{ number_format($item->purchase_cost, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Cost / Purchase Unit
                                            </th>

                                            <td class="text-primary fw-bold">
                                                ₹ {{ number_format($item->cost_per_purchase_unit, 6) }}
                                            </td>
                                        </tr>

                                    </table>

                                </td>

                                {{-- Costing Details --}}
                                <td>

                                    <table class="table table-sm table-borderless inner-table mb-0">

                                        <tr>

                                            <th width="55%">
                                                Base UOM
                                            </th>

                                            <td>
                                                {{ strtoupper($item->base_uom ?? '-') }}
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>
                                                Conversion
                                            </th>

                                            <td>
                                                {{ number_format($item->conversion_to_base, 3) }}
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>
                                                Gross Cost/Base
                                            </th>

                                            <td class="text-info fw-bold">
                                                ₹ {{ number_format($item->gross_cost_per_base_unit, 9) }}
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>
                                                Yield %
                                            </th>

                                            <td>
                                                {{ number_format($item->yield_percent, 2) }}%
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>
                                                Net Cost/Base
                                            </th>

                                            <td class="text-success fw-bold">
                                                ₹ {{ number_format($item->net_cost_per_base_unit, 9) }}
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>
                                                Wastage %
                                            </th>

                                            <td class="text-danger fw-bold">
                                                {{ number_format($item->wastage_allowance_percent, 2) }}%
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>
                                                Costing Rate
                                            </th>

                                            <td class="fw-bold">
                                                ₹ {{ number_format($item->costing_rate, 9) }}
                                            </td>

                                        </tr>

                                    </table>

                                </td>

                                {{-- Supplier --}}
                                <td>

                                    {{ $item->supplier ?? '-' }}

                                </td>

                                {{-- Updated --}}
                                <td class="text-center">

                                    @if($item->last_updated)

                                        {{ \Carbon\Carbon::parse($item->last_updated)->format('d-m-Y') }}

                                    @else

                                        -

                                    @endif

                                </td>

                              

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center text-muted py-4">

                                    No Ingredient Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection