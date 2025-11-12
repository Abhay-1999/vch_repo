<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      background: #fff;
      text-align: center;
    }
    .token-container {
      width: 80mm;
      margin: 0 auto;
      padding-top: 10mm;
    }
    .token-box {
      font-size: 20px;
      font-weight: bold;
      border: 2px dashed #000;
      display: inline-block;
      padding: 4px 12px;
      margin: 0;
    }
    @media print {
      * {
        margin: 0 !important;
        padding: 0 !important;
        box-sizing: border-box;
      }
      html, body {
        height: auto;
        background: #fff !important;
      }
      .token-container {
        padding-top: 0;
      }
      button {
        display: none !important;
      }
      .table td {
        font-size: 14px;
        padding: 6px 4px;
      }
      @page {
        size: 80mm auto;
        margin: 0;
      }
    }
  </style>
</head>
<body>

  {{-- ************** SECTION 1: TOKEN COPY ************** --}}
  <div id="invoiceContent">
  <div class="container" >
    <div class="invoice-box p-3">

      <div class="token-container">
        <div class="token-box">
          Token No: <strong>{{ $hd_data->tran_no }}</strong>
        </div>
      </div>

      <div class="text-center small mt-2 mb-3">
        <div>Payment Mode: <strong>{{ $paymentMode }}</strong></div>
        <div>Order Time: <strong>{{ date('d M Y, h:i A', strtotime($hd_data->created_at)) }}</strong></div>
        @if($hd_data->order_id)
          <p>Order Id: <strong>{{ $hd_data->order_id }}</strong></p>
        @endif
        @if($hd_data->otp)
          <p>OTP: <strong>{{ $hd_data->otp }}</strong></p>
        @endif
      </div>

      <table class="table table-bordered mb-2">
        <thead class="table-dark">
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th class="text-end">Rate</th>
            <th class="text-end">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dt_data as $d_data)
          <tr>
            @php $itemAmt = $d_data->amount + $d_data->item_gst; @endphp
            <td>{{ $d_data->item_hdesc }}</td>
            <td>{{ $d_data->item_qty ?: $d_data->item_gm . ' gm' }}</td>
            <td class="text-end">{{ number_format($d_data->item_rate, 2) }}</td>
            <td class="text-end">{{ number_format($itemAmt, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="text-end">
        @if($hd_data->discount)
          @php
              $discountAmount = ($hd_data->gross_amt * $hd_data->discount) / 100;
              $finalAmount = $hd_data->gross_amt - $discountAmount;
          @endphp
          <p>Gross: ₹{{ number_format($hd_data->gross_amt, 2) }}</p>
          <p>Discount ({{ $hd_data->discount }}%): − ₹{{ number_format($discountAmount, 2) }}</p>
          <p class="fw-bold">Final: ₹{{ number_format($finalAmount, 2) }}</p>
        @else
          <p>Total: ₹{{ number_format(round($hd_data->paid_amt), 2) }}</p>
        @endif
      </div>
    </div>
  </div>

  <hr style="border-top: 1px dashed #000; width:80mm; margin:8px auto;">

  {{-- ************** SECTION 2: RETAIL INVOICE BILL ************** --}}
  <div class="invoice-box p-3" style="width:80mm; margin:auto;">

    <div class="text-center">
      <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="height:40px; margin-right:10px;">
    </div>

    <div class="text-center mb-2">
      <h5 class="invoice-header mb-1">{{ $rest_data->rest_name }}</h5>
      <small>
        {{ $rest_data->rest_add2 }} {{ $rest_data->rest_city }}<br>
        Phone: +91 {{ $rest_data->rest_contact }}<br>
        GSTIN: {{ $rest_data->rest_gstin }}<br>
        FSSAI: {{ $rest_data->rest_fssai }}<br>
        Website: {{ $rest_data->rest_website }}
      </small>
    </div>



    <div class="text-center mb-2">
      <strong>RETAIL INVOICE</strong>
    </div>

    <div class="mb-2 text-start">
      <p>Invoice No: <strong>{{ $invoiceNo }}</strong></p>
      <p>Invoice Date: <strong>{{ date('d-m-Y', strtotime($hd_data->invoice_date)) }}</strong></p>
      <p>Token No: <strong>{{ $hd_data->tran_no }}</strong></p>
      <p>Payment Mode: <strong>{{ $paymentMode }}</strong></p>
      @if($hd_data->order_id)
        <p>Order Id: <strong>{{ $hd_data->order_id }}</strong></p>
      @endif
      @if($hd_data->otp)
        <p>OTP: <strong>{{ $hd_data->otp }}</strong></p>
      @endif
    </div>

    <table class="table table-bordered mb-2">
      <thead class="table-light">
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th class="text-end">Rate</th>
          <th class="text-end">Amt</th>
        </tr>
      </thead>
      <tbody>
        <?php $itemgst = $itemAmt = 0; ?>
        @foreach($dt_data as $d_data)
          <?php 
            $itemgst += $d_data->item_gst;
            $itemAmt += $d_data->amount;
          ?>
          <tr>
            <td>{{ $d_data->item_hdesc }}</td>
            <td>{{ $d_data->item_qty ? $d_data->item_qty : $d_data->item_gm . 'g' }}</td>
            <td class="text-end">{{ number_format($d_data->item_rate, 2) }}</td>
            <td class="text-end">{{ number_format($d_data->amount, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end total-box mt-1">
      <small>HSN/SAC: 996331</small><br>
      @if($hd_data->discount)
        @php
          $discountAmount = ($hd_data->gross_amt * $hd_data->discount) / 100;
          $finalAmount = $hd_data->gross_amt - $discountAmount;
        @endphp
        <p>Total: ₹{{ number_format($itemAmt, 2) }}</p>
        <p>SGST 2.5%: ₹{{ round($itemgst/2, 2) }}</p>
        <p>CGST 2.5%: ₹{{ round($itemgst/2, 2) }}</p>
        <p>Discount {{ $hd_data->discount }}%: − ₹{{ number_format($discountAmount, 2) }}</p>
        <p><strong>Net Payable: ₹{{ number_format($finalAmount, 2) }}</strong></p>
      @else
        <p>Total: ₹{{ number_format($itemAmt, 2) }}</p>
        <p>SGST 2.5%: ₹{{ round($itemgst/2, 2) }}</p>
        <p>CGST 2.5%: ₹{{ round($itemgst/2, 2) }}</p>
        <p><strong>Net Payable: ₹{{ number_format(round($hd_data->paid_amt), 2) }}</strong></p>
      @endif
    </div>

    <div class="text-center mt-2">
      <p>Thanks for visiting <strong>{{ $rest_data->rest_name }}</strong><br>Have a nice day!</p>
    </div>
  </div>
  </div>

  <div class="text-center mt-3 mb-3">
    <button class="btn btn-success" onclick="saveInvoiceAsImage()">Save as Image</button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script>
    function saveInvoiceAsImage() {
      const element = document.getElementById("invoiceContent");
      html2canvas(element, {
        scale: 2,
        useCORS: true,
        allowTaint: false
      }).then(canvas => {
        const imgData = canvas.toDataURL("image/png");
        const link = document.createElement('a');
        link.href = imgData;
        link.download = 'invoice_{{ $hd_data->tran_no }}.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      });
    }
  </script>

</body>
</html>
