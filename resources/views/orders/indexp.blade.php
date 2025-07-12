@extends('auth.layouts.app')

@section('content')
<style>
    a.disabled {
  pointer-events: none;
  opacity: 0.6;
  cursor: not-allowed;
}

</style>
    <h1 class="text-center">Pending Orders Invoices</h1>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Token No/Time</th>
                <th>Invoice Detail</th>
                <th>Item Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="orderTable">
         @include('orders.ordersp_table', ['orders' => $orders,'order_arr'=>$order_arr,'role'=>$role])
        </tbody>
    </table>
    <script>
    function makePayment(tran_no) {
        if (!confirm("Are you sure you want to mark this order as delivered?")) return;

        fetch("{{ route('orders.makePayment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tran_no: tran_no })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                reloadOrders(); // Reload full order list
            } else {
                alert('Failed to update order.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong.');
        });
    }

    function reloadOrders() {
        fetch("{{ route('ordersp.refresh') }}")
            .then(response => response.text())
            .then(html => {
                document.getElementById('orderTable').innerHTML = html;
            });
    }

    setInterval(reloadOrders, 10000);
</script>

@endsection
