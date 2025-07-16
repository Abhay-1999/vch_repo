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
    <h1 class="text-center">Delivered Orders</h1>
    <div class="d-flex justify-content-center my-4">
    <div class="card p-3 shadow-sm" style="max-width: 350px; width: 100%;">
        <label for="filterDate" class="form-label fw-bold mb-2 text-center">📅 Filter by Date</label>
        <input type="date" id="filterDate" name="filterDate" value="{{ date('Y-m-d') }}" class="form-control text-center">
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">


    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">

    <div class="dd-dashboard-right-flex" id="orderTable">

        @include('orders.orders_table_delivered', compact('orders','order_arr','role','order_arr_item','order_arr_item_f'))


         </div>
         </div>

         <script>
    function reloadOrders() {
        const selectedDate = document.getElementById('filterDate').value;

        fetch("{{ route('orders.refreshdelivered') }}?date=" + selectedDate)
            .then(response => response.text())
            .then(html => {
                document.getElementById('orderTable').innerHTML = html;
            });
    }

    // Reload when date changes
    document.getElementById('filterDate').addEventListener('change', reloadOrders);

    // Auto reload every 10 seconds
    setInterval(reloadOrders, 10000);

    // $('#manual-print-bill').click(() => {
    // if (!lastOrderId) return alert('No order to print!');
    //     handlePrint(lastOrderId, 'bill');
    // });

    function printBill(lastOrderId,date){
        handlePrint(lastOrderId,date, 'bill');
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
        win.document.write('</body></html>');
        win.document.close();

        win.onload = function () {
            win.focus();
            win.print();
            win.close();
        };
    }
</script>

   

@endsection
