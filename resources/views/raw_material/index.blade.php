@extends('auth.layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-3">

        <h4>Raw Materials List</h4>

        <a href="{{ url('admin/raw-materials/create') }}"
           class="btn btn-primary">

            Add Raw Material

        </a>

    </div>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>

                <th>ID</th>

                <th>Material Name</th>

                <th>Code</th>

                <th>Unit</th>

                <th>Opening Stock</th>

                <th>Current Stock</th>

                <th>Purchase Rate</th>

                <th>Minimum Alert</th>

            </tr>

        </thead>

        <tbody>

            @foreach($materials as $material)

            <tr>

                <td>{{ $material->id }}</td>

                <td>{{ $material->material_name }}</td>

                <td>{{ $material->material_code }}</td>

                <td>{{ $material->unit->short_name ?? '' }}</td>

                <td>{{ $material->opening_stock }}</td>

                <td>{{ $material->current_stock }}</td>

                <td>{{ $material->purchase_rate }}</td>

                <td>{{ $material->min_stock_alert }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection