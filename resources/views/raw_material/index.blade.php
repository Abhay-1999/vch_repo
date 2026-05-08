@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Raw Material List</h4>

        <a href="{{ route('raw_mat_form') }}" class="btn btn-primary btn-sm">
            + Add Material
        </a>

    </div>

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>GST</th>
                <th>MRP</th>
                <th>Min Stock</th>
                <th>Status</th>
            </tr>


        </thead>

        <tbody>

            @forelse($materials as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->material_code }}</td>
                <td>{{ $item->material_name }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->gst_rate }}%</td>
                <td>₹{{ $item->mrp }}</td>
                <td>{{ $item->min_stock_level }}</td>
                <td>
                    @if($item->active == 1)
                        <span class="text-success">Active</span>
                    @else
                        <span class="text-danger">Inactive</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No Data Found</td>
            </tr>
            @endforelse
=======
          

        </tbody>

    </table>

    <div class="mt-3">
        {{ $materials->links() }}
    </div>

</div>

@endsection