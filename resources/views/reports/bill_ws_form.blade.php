@extends('auth.layouts.app')
@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card text-center">
                <div class="card-body" style="background-color: aliceblue;">
                    <h3 class="card-title mb-4">SALES REGISTER</h3>
                    <form id="cashbookForm" action="{{ route('bill_ws_data') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start_date" onchange="checkDateInput(`start_date`)" name="start_date" class="form-control mt-2" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" onchange="checkDateInput(`end_date`)" name="end_date" class="form-control mt-2" max="{{ date('Y-m-d') }}" >
                            </div>
                        </div>
                         <div class="row justify-content-center">
                            <div class="col-md-5 mb-3 text-center">
                                <label for="payment_mode">Payment Mode</label>
                                <select class="form-control mt-2" name="payment_mode" id="payment_mode">
                                    <option value="">All</option>
                                    <option value="C" {{ request('payment_mode') == 'C' ? 'selected' : '' }}>Cash</option>
                                    <option value="O" {{ request('payment_mode') == 'O' ? 'selected' : '' }}>Online</option>
                                    <option value="U" {{ request('payment_mode') == 'U' ? 'selected' : '' }}>Counter UPI</option>
                                    <option value="Z" {{ request('payment_mode') == 'Z' ? 'selected' : '' }}>Zomato</option>
                                    <option value="S" {{ request('payment_mode') == 'S' ? 'selected' : '' }}>Swiggy</option>
                                </select>
                            </div>
                        </div>
                        <input type="submit" value="Detail" class="btn btn-primary" id="viewBtn" disabled>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkDateInput() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const viewBtn = document.getElementById('viewBtn');

        if (startDate && endDate) {
            viewBtn.disabled = false;
        } else {
            viewBtn.disabled = true;
        }
    }
</script>

@endsection