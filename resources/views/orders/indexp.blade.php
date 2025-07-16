@extends('auth.layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-y: auto; /* ensures vertical scrolling */
    }

    .dd-dashboard-right-flex {
        overflow-y: auto;
        max-height: 80vh; /* or adjust based on your layout */
    }

    a.disabled {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

    <h1 class="text-center">Pending Orders</h1>
    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">

    <div class="dd-dashboard-right-flex" id="orderTable">

         @include('orders.ordersp_table', ['orders' => $orders,'order_arr'=>$order_arr,'role'=>$role])
         </div>
         </div>

    <script>


    function markDelivered(tran_no) {
        if (!confirm("Are you sure you want to mark this order as delivered?")) return;

        fetch("{{ route('orders.deliver') }}", {
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

    function updateStatus(tran_no,flag) {
        if (!confirm("Are you sure you want to Update this order?")) return;

        fetch("{{ route('orders.flag') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tran_no: tran_no,flag:flag })
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
