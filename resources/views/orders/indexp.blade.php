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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <h1 class="text-center">Pending Orders</h1>
    <div class="d-flex justify-content-center gap-4 mt-2 mb-4 flex-wrap small text-muted">

    <div><i class="fa-solid fa-print text-success"></i> Print Bill</div>

    <div><i class="fa-solid fa-pause text-warning"></i> Mark Hold</div>

    <div><i class="fa-solid fa-check text-primary"></i> Ready to Serve</div>

    <div><i class="fa-solid fa-pen-to-square text-dark"></i> Edit Order</div>

</div>
    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">

    <div class="dd-dashboard-right-flex" id="orderTable">

         @include('orders.ordersp_table', ['orders' => $orders,'order_arr'=>$order_arr,'role'=>$role])
         </div>
         </div>

    <script>


    function markHold(tran_no) {
        if (!confirm("Are you sure you want to mark this order as delivered?")) return;

        fetch("{{ route('orders.hold') }}", {
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


      function handlePrint(orderId,date, type) {

        $.post('/print-content', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            trans_no: orderId,
            type: type,
            date: date
        }, function(res) {
            console.log(res); //
            if (res.html) {
            
                    printHtml(res.html);
                
            } else {
                alert('No HTML returned.');
            }
        }).fail(function() {
            alert('Print failed due to server error.');
        });
    }

     function printHtml(html) {
        const win = window.open('', '', 'width=1200px,height=800px');

        win.document.open();
        win.document.write('<html><head><title>Print</title></head><body>');
        win.document.write(html);
        win.document.close();

        win.onload = function () {
            win.focus();
            win.print();
            win.close();
        };
    }


    setInterval(reloadOrders, 10000);

     function markDelivered(tran_no) {

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
    
</script>

@endsection
