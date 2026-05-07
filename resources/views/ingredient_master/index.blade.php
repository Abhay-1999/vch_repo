@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Ingredient List</h3>

        <a href="{{ route('ingredient.create') }}" class="btn btn-success">
            + Add Ingredient
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Ingredient Name</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ingredients as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->ingredient_name }}</td>
                            <td>{{ $item->unit ?? '-' }}</td>
                            <td>{{ $item->rate ?? '-' }}</td>
                            <td>
                                <a href="{{ route('ingredient.edit', $item->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    Edit
                                </a>

                                <form action="{{ route('ingredient.delete', $item->id) }}" 
                                      method="POST" 
                                      style="display:inline;">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No Ingredients Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection