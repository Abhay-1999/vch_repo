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
    <h1 class="text-center">Change Orders</h1>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div style="overflow-y: auto; max-height: calc(100vh - 100px); padding-bottom: 50px;">

    <div class="dd-dashboard-right-flex" id="orderTable">
    
@foreach($orders as $k => $order)

                <div class="dd-dashboard-right-box dd-dash-redBox">
                    <div class="dd-timer-head">
                       <div class="dd-timer-sec-left">
                            <div class="dd-no d-flex">
                                <h1>Token No:-{{ $order->tran_no }}</h1>
                            </div>
                       </div>
                       <div class="dd-timer-sec-right">
                            <ul>
                          
                                <li>
                                    <span class="badge bg-primary font-weight-bolder">
                                       @if($order->payment_mode=="O")
                                            Online
                                       @elseif($order->payment_mode=="Z")
                                             Zomato
                                        @elseif($order->payment_mode=="S")
                                            Swiggy
                                        @elseif($order->payment_mode=="U")
                                           Counter UPI 
                                        @else
                                            Cash
                                       @endif
                                    </span>
                                </li>
                               
                            </ul>
                       </div>
                    </div>
                    <div class="dd-timer-body" style="padding: 10px; font-family: sans-serif;">
                        <!-- Item Group 1 -->
                        <ul style="list-style: none; padding: 0; margin: 0 0 10px 0;">
                            @if(!empty($order_arr[$order->tran_no]))
                                @php
                                    $items = $order_arr[$order->tran_no];
                                @endphp
                                @foreach($items as $index => $item)
                                    <li style="font-weight: bold; margin-bottom: 4px;">
                                        <input type="checkbox"
                                            class="item-checkbox"
                                            data-tran="{{ $order->tran_no }}"
                                            data-item_code="{{ $order_arr_item[$order->tran_no][$index] }}"
                                            data-index="{{ $index }}"
                                            data-total="{{ count($items) }}"

                                            @if($order_arr_item_f[$order->tran_no][$order_arr_item[$order->tran_no][$index]]=='D') checked disabled @else  @endif
                                        >
                                        {{ $item }}
                                    </li>
                                @endforeach
                            @else
                                <span class="text-muted">No Detail</span>
                            @endif
                        </ul>
                    </div>

                    <div class="dd-timer-footer ">
                       

                        <a href="javascript:void(0);" onclick="markDelivered('{{ $order->tran_no }}','{{ $order->payment_mode }}')" class="dd-big-btn dd-big-green-btn">
                        @if($order->payment_mode=='C')
                                Cash
                        @else
                            Counter UPI
                        @endif
                    </a>
                    </div>
                </div>
 
             
@endforeach

         </div>
         </div>

    <script>



    function markDelivered(tran_no,flag) {
        if (!confirm("Are you sure you want to Update this order?")) return;

        fetch("{{ route('change.paymentMode') }}", {
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
                location.reload(); // Reload full order list
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
