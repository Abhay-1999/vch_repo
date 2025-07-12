

@extends('auth.layouts.app')

@section('content')
    <h1 class="text-center">Items</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Item Desc</th>
                <th>Item Qty</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $k => $order)
            <tr>
                <td>{{ $k + 1 }}</td>
                <td>{{ $order->tran_no }}</td>
                <td>{{ $order->item_desc  }}</td>
                <td>{{ $order->item_qty }}</td>
                <td><a href="#">Order Proceed</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
