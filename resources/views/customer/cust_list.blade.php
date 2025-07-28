@extends('auth.layouts.app')

@section('content')
<div class="container" id="print-content">
    {{-- Print-specific styles --}}
    <style>
        @media print {
            #printButton {
                display: none;
            }
            table#myTable {
                border-collapse: collapse;
                width: 100%;
                margin-top: 10px;
                border: 1px solid black;
            }
            thead {
                border-top: 1px solid black;
                border-bottom: 1px solid black;
            }

            tbody td, thead th {
                border: 1px solid black;
                padding-left: 10px;
                line-height: 1.3;
            }

            table#firstTable {
                border: none;
            }

            table#firstTable td, table#firstTable th {
                border: none !important;
            }

            .text-right {
                padding-right: 5px;
            }

            th {
                font-size: 12px;
            }

            td {
                font-size: 10px;
            }
        }

        .text-right {
            text-align: right;
        }
    </style>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMsg">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <h3>Customer List</h3>
        </div>    
        <div class="col-6 text-right">
            <button class="btn btn-primary mb-3" id="addCustomerBtn">Add Customer</button>
        </div>
    </div>
    <table class="table table-bordered" id="myTable">
        <thead>
            <tr>
                <th>S.NO</th>
                <th>NAME</th>
                <th>ADDRESS</th>
                <th>MOBILE</th>
                <th>GST NO</th>
                <th>BUSINESS NAME</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($custData as $index => $cust)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $cust->name }}</td>
                <td>{{ $cust->address }}</td>
                <td>{{ $cust->mob_no }}</td>
                <td>{{ $cust->gst_no }}</td>
                <td>{{ $cust->comp_name }}</td>
                <td>
                    <button class="btn btn-sm btn-info editBtn" data-id="{{ $cust->id }}">Edit</button>
                    <a href="{{ route('cust.delete', $cust->id) }}"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this customer?')">
                       Delete
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Customer Modal --}}
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="customerForm" method="POST" action="{{ route('cust_save') }}">
            @csrf
            <input type="hidden" id="cust_id" name="id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Customer</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Name</label>
                        <input type="text" id="cust_name" name="name" class="form-control" value="{{ old('name') }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label>Address</label>
                        <input type="text" id="cust_address" name="address" class="form-control" value="{{ old('address') }}">
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label>Mobile</label>
                        <input type="text" id="cust_mob" name="mob_no" class="form-control numbers" maxlength="10" value="{{ old('mob_no') }}">
                        @error('mob_no') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label>GST No</label>
                        <input type="text" id="cust_gst" name="gst_no" class="form-control" maxlength="15" value="{{ old('gst_no') }}">
                        @error('gst_no') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label>Business Name</label>
                        <input type="text" id="cust_comp" name="comp_name" class="form-control" value="{{ old('comp_name') }}">
                        @error('comp_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('input', function(event) {
        const target = event.target;
        if (target.classList.contains('numbers')) {
            // Remove non-numeric characters
            target.value = target.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        }
    });
    // Hide success message after 3 seconds
    setTimeout(() => {
        $('#successMsg').fadeOut('slow');
    }, 3000);

    $(document).ready(function () {
        $('#addCustomerBtn').click(function () {
            $('#customerForm')[0].reset();
            $('#modalTitle').text('Add Customer');
            $('#cust_id').val('');
            $('.text-danger').text('');
            $('#customerModal').modal('show');
        });

        $('.editBtn').click(function () {
            let id = $(this).data('id');
            $.get("{{ url('admin/cust-edit') }}/" + id, function (response) {
                let c = response.customer;
                $('#modalTitle').text('Edit Customer');
                $('#cust_id').val(c.id);
                $('#cust_name').val(c.name);
                $('#cust_address').val(c.address);
                $('#cust_mob').val(c.mob_no);
                $('#cust_gst').val(c.gst_no);
                $('#cust_comp').val(c.comp_name);
                $('#customerModal').modal('show');
            });
        });

        @if ($errors->any())
            $('#customerModal').modal('show');
        @endif
    });
</script>
@endsection
