@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Recipe Cost List</h4>
        <a href="{{ url('admin/recipe-cost/create') }}" class="btn btn-primary">
            + Add Recipe
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Recipe Name</th>
                        <th>Total Cost</th>
                        <th>Portion Cost</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if(isset($recipes) && count($recipes) > 0)
                        @foreach($recipes as $key => $recipe)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $recipe->name }}</td>
                                <td>{{ $recipe->total_cost }}</td>
                                <td>{{ $recipe->portion_cost }}</td>
                                <td>
                                    <a href="{{ url('recipe-cost/'.$recipe->id.'/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="{{ url('recipe-cost/'.$recipe->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No Recipes Found</td>
                        </tr>
                    @endif
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection