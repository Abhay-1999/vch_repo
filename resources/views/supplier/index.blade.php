@extends('auth.layouts.app')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>Supplier List</h4>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">+ Add Supplier</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Mobile</th>
                <th>City</th>
                <th>Status</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $key => $sup)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $sup->supplier_id }}</td>
                <td>{{ $sup->supplier_name }}</td>
                <td>{{ $sup->category }}</td>
                <td>{{ $sup->mobile_no }}</td>
                <td>{{ $sup->city }}</td>
                <td>{{ $sup->status }}</td>
                <td>

                    <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $sup->supplier_id  }}">
                        Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $suppliers->links() }}
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('.deleteBtn').click(function () {
    if (!confirm('Delete this supplier?')) return;

    let id = $(this).data('id');

    let url = "{{ route('supplier.delete', ':id') }}";
    url = url.replace(':id', id);

    $.ajax({
        url: url,
        type: 'POST', // 👈 important
        data: {
            _token: "{{ csrf_token() }}",
            _method: "DELETE" // 👈 Laravel ko batane ke liye
        },
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function (err) {
            console.log(err);
            alert('Delete failed');
        }
    });
});
</script>

@endsection