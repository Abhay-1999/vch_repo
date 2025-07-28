@extends('auth.layouts.app')

@section('content')
        <!-- Sidebar -->
        @php
        $tOrder = \Illuminate\Support\Facades\DB::table('order_hd')
                    ->count();

    $tOrdert = \Illuminate\Support\Facades\DB::table('order_hd')
    ->where('tran_date', date('Y-m-d'))
    ->count();

    $pOrdert = \Illuminate\Support\Facades\DB::table('order_hd')
    ->where('tran_date', date('Y-m-d'))
    ->where('flag','!=','D')
    ->count();

    $Revenue = \Illuminate\Support\Facades\DB::table('order_hd')
    ->where('tran_date', date('Y-m-d'))
    ->where('flag','D')
    ->sum('paid_amt');
    @endphp
        <!-- <div class="topbar">
                <h5 class="mb-0">Dashboard</h5>
                <span class="text-muted">Admin: {{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
            </div> -->

            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm p-3">
                            <h5>Total Orders</h5>
                            <p class="fs-4 text-primary">{{ $tOrder }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm p-3">
                            <h5>Total Orders Today</h5>
                            <p class="fs-4 text-warning">{{ $tOrdert }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm p-3">
                            <h5>Pending Orders Today</h5>
                            <p class="fs-4 text-warning">{{ $pOrdert }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm p-3">
                            <h5>Revenue Today</h5>
                            <p class="fs-4 text-success">₹{{ $Revenue }}</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <h2>Welcome to the Vijay Chat Dashboard</h2>
                </div>
            </div>

  
    @endsection
