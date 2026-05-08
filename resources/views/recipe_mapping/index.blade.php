@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Recipe Mapping List</h4>

        <a href="{{ route('recipe_mapping_form') }}" class="btn btn-primary btn-sm">
            + Add Recipe
        </a>
    </div>
      @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
     @endif
    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>#</th>
                <th>Recipe ID</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Material Code</th>
                <th>Material Name</th>
                <th>Selling Price</th>
                <th>Effective Cost</th>
                <th>Created By</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @forelse($recipes as $key => $row)

            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $row->recipe_id }}</td>
                <td>{{ $row->item_code }}</td>
                <td>{{ $row->item_name }}</td>
                <td>{{ $row->material_code }}</td>
                <td>{{ $row->material_name }}</td>
                <td>{{ $row->selling_price }}</td>
                <td>{{ $row->effective_cost }}</td>
                <td>{{ $row->created_by }}</td>

                <td>
                    @if($row->active == 1)
                        <span class="text-success">Active</span>
                    @else
                        <span class="text-danger">Inactive</span>
                    @endif
                </td>
            </tr>

            @empty

            <tr>
                <td colspan="10" class="text-center">No Data Found</td>
            </tr>

            @endforelse

        </tbody>

    </table>
</div>
@endsection