@foreach($orders as $k => $order)
<tr>
    <td>{{ $k + 1 }}</td>

    <!-- Table No. and Time -->
    <td>
        <span class="badge bg-primary">{{ $order->tran_no }}</span>
        <span class="badge bg-warning">{{ date('h:i A', strtotime($order->tran_time)) }}</span>
    </td>
    <td>
        <span class="badge bg-primary">Invoice no-{{ $order->invoice_no }}</span><br>
        <span class="badge bg-warning">Invoice Date{{ date('d-m-Y ', strtotime($order->invoice_date)) }}</span><br>
        <span class="badge bg-info">Invoice Time{{ date('h:i A', strtotime($order->invoice_time)) }}</span><br>
    </td>
    <!-- Order Items -->
    <td>
        @if(!empty($order_arr[$order->tran_no]))
            @php
                $items = $order_arr[$order->tran_no];
                $colors = ['success', 'warning', 'danger', 'info', 'secondary', 'dark', 'primary'];
            @endphp
            @foreach($items as $index => $item)
                <span class="badge bg-{{ $colors[$index % count($colors)] }} text-white me-1">{{ $item }}</span>
            @endforeach
        @else
            <span class="text-muted">No Detail</span>
        @endif
    </td>

    <!-- Order Status -->
    <td>
  
        @if($order->flag == 'P')
            <span class="badge bg-primary p-2 mb-1">
                <i class="fas fa-fire me-1"></i> Processing
            </span><br>
        @elseif($order->flag == 'S')
            <span class="badge bg-warning text-dark p-2">
                <i class="fas fa-clock me-1"></i> Pending
            </span>
        @elseif($order->flag == 'R')
            <span class="badge bg-success p-2">
                <i class="fas fa-check-circle me-1"></i> Ready to Serve
            </span>
        @else
            <span class="badge bg-info p-2">Order Delivered</span>
        @endif
        @if($order->status_trans=='pending')
        <a href="#" onclick="makePayment('{{ $order->tran_no }}')" class="btn btn-secondary btn-sm mt-1">
                Mark to paid
            </a>
        @else
            <a href="#" class="btn btn-secondary btn-sm mt-1">
            Bill paid
            </a>
        @endif
</td>

</tr>
@endforeach
