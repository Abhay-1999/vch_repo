       
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
                                     
                                        {{ $item }}
                                    </li>
                                @endforeach
                            @else
                                <span class="text-muted">No Detail</span>
                            @endif
                        </ul>
                    </div>

                    <div class="text-center mt-4">
                <div class="btn-group" role="group" aria-label="Action Buttons">
                    <a href="javascript:void(0);" onclick="printBill({{ $order->tran_no }}, '{{ $order->tran_date }}')"
                    class="btn btn-sm btn-primary rounded-pill px-3">
                        🖨️ Print Bill
                    </a>
                    <a href="https://wa.me/?text=Your+bill+details+here" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                        📤 WhatsApp
                    </a>
                </div>
            </div>




                </div>

             
@endforeach