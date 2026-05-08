@extends('auth.layouts.app')

@section('content')

<div class="container">

    <h4 class="mb-4">
        Current Stock Report
    </h4>

    <table class="table table-bordered">

        <thead class="table-dark">

            <tr>

                <th>Material</th>

                <th>Current Stock</th>

                <th>Unit</th>

            </tr>

        </thead>

        <tbody>

            @foreach($stocks as $stock)

            <tr>

                <td>{{ $stock->material_name }}</td>

                <td>{{ $stock->current_stock }}</td>

                <td>{{ $stock->unit->short_name ?? '' }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection