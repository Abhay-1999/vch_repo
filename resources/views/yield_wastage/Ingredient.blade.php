@extends('auth.layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                Menu Ingredient Stock Report
            </h4>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>
                            <th>S.No</th>
                            <th>Menu</th>
                            <th>Ingredient</th>
                            <th>Used Qty</th>
                            <th>Purchase Qty</th>
                            <th>Remaining Qty</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($reports as $key => $row)

                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    {{ $row->item_name ?? '-' }}
                                </td>

                                <td>
                                    {{ $row->ingredient_name ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format($row->used_qty, 2) }}
                                </td>

                                <td>
                                    {{ number_format($row->purchase_qty, 2) }}
                                </td>

                                <td>
                                    {{ number_format($row->purchase_qty - $row->used_qty, 2) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-danger">
                                    No Data Found
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