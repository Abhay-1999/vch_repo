<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Store-wise Tokens</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html, body {
      margin: 0 !important;
      padding: 0 !important;
      background: #fff !important;
      font-size: 14px;
      text-align: center;
      width: 72mm;   /* Fixed to TVS RP-3230 paper width */
    }

    .token-wrapper {
      width: 72mm;
      margin: 0 auto;
      padding: 2mm 0;        /* minimal padding, no extra blank */
      page-break-after: always;
    }

    .token-box {
      font-size: 18px;
      font-weight: bold;
      border: 2px dashed #000;
      display: inline-block;
      padding: 2px 8px;
      margin-bottom: 6px;
    }

    .table td, .table th {
      font-size: 16px;
      padding: 4px 2px;
    }

    @media print {
      * {
        box-sizing: border-box;
      }

      html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        width: 72mm !important;
      }

      .token-wrapper {
        border-bottom: none;  /* no extra line at bottom */
        page-break-after: always;
      }

      .no-print {
        display: none !important;
      }

      @page {
        size: 72mm auto;  /* width fixed, height grows */
        margin: 0;        /* remove all top/bottom margins */
      }
    }
  </style>
</head>
<body>
@if(!in_array($paymentMode, ['Zomato', 'Swiggy']))

  @php
    $itemsGroupedByStore = $dt_data->groupBy('store')->sortKeys();
  @endphp
  @foreach($itemsGroupedByStore as $storeId => $storeItems)
    <div class="token-wrapper" id="token-{{ $storeId }}">
      <div class="token-box">Token No: {{ $hd_data->tran_no }}</div>

      <div class="mt-1 mb-2">
        <div>Store: <strong>{{ $storeId }}</strong></div>
        <div>Payment Mode: <strong>{{ $paymentMode }}</strong></div>
        <div>Time: <strong>{{ date('d M Y, h:i A', strtotime($hd_data->created_at)) }}</strong></div>

        @if($hd_data->order_id)
          <div>Order ID: <strong>{{ $hd_data->order_id }}</strong></div>
        @endif
        @if($hd_data->otp)
          <div>OTP: <strong>{{ $hd_data->otp }}</strong></div>
        @endif
      </div>

      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th class="text-end">Rate</th>
            <th class="text-end">Amount</th>
          </tr>
        </thead>
        <tbody>
          @php $storeTotal = 0; @endphp
          @foreach($storeItems as $item)
            @php
              $itemAmt = $item->amount + $item->item_gst;
              $storeTotal += $itemAmt;
            @endphp
            <tr>
              <td>{{ $item->item_hdesc }}</td>
              <td>{{ $item->item_qty ?: $item->item_gm . ' gm' }}</td>
              <td class="text-end">{{ number_format($item->item_rate, 2) }}</td>
              <td class="text-end">{{ number_format($itemAmt, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="text-end mt-2">
        <strong>Store Total: ₹{{ number_format($storeTotal, 2) }}</strong>
      </div>
    </div>
  @endforeach

  <div class="text-center mt-3 no-print">
    <button class="btn btn-primary" onclick="window.print()">🖨️ Print</button>
  </div>

@else
  <div class="token-wrapper">
    <div class="token-box">Token No: {{ $hd_data->tran_no }}</div>

    <div class="mt-1 mb-2">
      <div>Payment Mode: <strong>{{ $paymentMode }}</strong></div>
      <div>Time: <strong>{{ date('d M Y, h:i A', strtotime($hd_data->created_at)) }}</strong></div>

      @if($hd_data->order_id)
        <div>Order ID: <strong>{{ $hd_data->order_id }}</strong></div>
      @endif
      @if($hd_data->otp)
        <div>OTP: <strong>{{ $hd_data->otp }}</strong></div>
      @endif
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th class="text-end">Rate</th>
            <th class="text-end">Amount</th>
          </tr>
        </thead>
        <tbody>
        @foreach($dt_data as $d_data)
          @php $itemAmt1 = $d_data->amount + $d_data->item_gst; @endphp
          <tr>
            <td>{{ $d_data->item_hdesc }}</td>
            <td>{{ $d_data->item_qty ?: $d_data->item_gm . ' gm' }}</td>
            <td class="text-end">{{ number_format($d_data->item_rate, 2) }}</td>
            <td class="text-end">{{ number_format($itemAmt1, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end mt-2">
      <strong>Total: ₹{{ number_format($hd_data->paid_amt, 2) }}</strong>
    </div>
  </div>

  <div class="text-center mt-3 no-print">
    <button class="btn btn-primary" onclick="window.print()">🖨️ Print</button>
  </div>
@endif




  <!-- html2canvas script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script>
    function saveAllInvoices() {
      const invoiceDivs = document.querySelectorAll('[id^=invoiceContent-]');
      invoiceDivs.forEach((div, index) => {
        html2canvas(div, {
          scale: 2,
          useCORS: true,
          allowTaint: false
        }).then(canvas => {
          const imgData = canvas.toDataURL("image/png");
          const link = document.createElement('a');
          link.href = imgData;
          link.download = `invoice_store_${index + 1}.png`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        });
      });
    }
  </script>

</body>
</html>
