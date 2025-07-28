
@foreach($orders as $k => $order)
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                       

                        @if($role == "2")
                        <a href="javascript:void(0);" onclick="markDelivered('{{ $order->tran_no }}')" class="dd-big-btn dd-big-green-btn">Ready to Serve</a>
                        @endif
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('change', '.item-checkbox', function () {
        if (this.checked) {
            

            let checkbox = $(this);
            let tran_no = checkbox.data('tran');
            let item_code = checkbox.data('item_code');
            let item_index = checkbox.data('index');
            let total = checkbox.data('total');
            checkbox.prop('disabled', true);
            $.ajax({
                url: "{{ route('order.item.update') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    tran_no: tran_no,
                    item_index: item_index,
                    item_code: item_code,
                },
                success: function (response) {
                    checkbox.prop('disabled', true);

                    // If last item is checked
                    let allCheckboxes = checkbox.closest('ul').find('input[type="checkbox"]');
                    let allChecked = allCheckboxes.filter(':checked').length;

                    if (allChecked === total) {
                        // Call another ajax to mark full order complete
                        $.ajax({
                            url: "{{ route('order.complete') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                tran_no: tran_no
                            },
                            success: function (res) {
                                reloadOrders(); // Reload full order list
                            }
                        });
                    }
                }
            });
        }
    });

</script>
             
@endforeach
