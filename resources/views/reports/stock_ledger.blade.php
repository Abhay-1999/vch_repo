@extends('auth.layouts.app')

@section('content')

<div class="container">

    <h4 class="mb-4">
        Stock Ledger Report
    </h4>

    <table class="table table-bordered">

        <thead class="table-dark">

            <tr>

                <th>Date</th>

                <th>Material</th>

                <th>Type</th>

                <th>Qty</th>

                <th>Before</th>

                <th>After</th>

            </tr>

        </thead>

        <tbody>

            @foreach($ledgers as $row)

            <tr>

                <td>{{ $row->created_at }}</td>

                <td>{{ $row->material->material_name ?? '' }}</td>

                <td>{{ $row->type }}</td>

                <td>{{ $row->qty }}</td>

                <td>{{ $row->stock_before }}</td>

                <td>{{ $row->stock_after }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection