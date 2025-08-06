<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tax Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    .invoice-box {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      font-size: 14px;
      max-width: 800px;
      margin: auto;
    }

    .invoice-header {
      font-size: 18px;
      font-weight: bold;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .total-box p {
      margin: 0;
    }

    .page-break {
      page-break-before: always;
    }

    @media print {
      body {
        margin: 0;
        padding: 0;
        background-color: #fff;
      }

      .invoice-box {
        box-shadow: none;
        border-radius: 0;
        padding: 10px;
        font-size: 12px;
        width: 80mm; /* 4 inch width */
      }

      .btn, .no-print {
        display: none !important;
      }

      .table th, .table td {
        padding: 4px !important;
        font-size: 12px !important;
      }

      .invoice-header {
        font-size: 16px;
      }
    }

    @media (max-width: 576px) {
      .invoice-header {
        font-size: 16px;
      }

      .invoice-box {
        padding: 10px;
        font-size: 13px;
      }

      .table-responsive {
        font-size: 12px;
      }
    }
  </style>
  <style media="print">
  .d-flex {
    display: flex !important;
  }
</style>
</head>
<body>
<div class="container page-break">
  <div class="invoice-box" id="invoiceContent">
  <div class="d-flex justify-content-between align-items-start mb-2" style="gap: 10px;">
  <div style="flex: 1 1 auto; min-width: 250px;">
    <h4 class="invoice-header mb-1">{{ $rest_data->rest_name }}</h4>
    <p class="mb-1">
      {{ $rest_data->rest_add2 }} {{ $rest_data->rest_city }}<br>
      Phone: +91 {{ $rest_data->rest_contact }}<br>
      GSTIN: {{ $rest_data->rest_gstin }}<br>
      FSSAI: {{ $rest_data->rest_fssai }}<br>
      Website: {{ $rest_data->rest_website }}
    </p>

    @if($cust_hd_data)
      <h5 class="mt-3 mb-1">{{ $cust_hd_data->name }}</h5>
      <p class="mb-1">
        {{ $cust_hd_data->address }}<br>
        Phone: +91 {{ $cust_hd_data->mob_no }}<br>
        GSTIN: {{ $cust_hd_data->gst_no }}
      </p>
    @endif
  </div>

  <div style="flex-shrink: 0; text-align: right;">
    <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="max-height: 80px;">
  </div>
</div>



    <h5 class="text-center">RETAIL INVOICE</h5>

    <div class="d-flex justify-content-between mb-3 flex-wrap">
      <div class="pe-3" style="flex: 1;">
        <p>Payment Mode: <strong>{{ $paymentMode }}</strong></p>
        <p>Invoice No: <strong>{{ $invoiceNo }}</strong></p>
        <p>Invoice Date: <strong>{{ date('d F Y', strtotime($hd_data->invoice_date)) }}</strong></p>
      </div>
      <div class="text-end ps-3" style="flex: 1;">
        <p>Token No: <strong>{{ $hd_data->tran_no }}</strong></p>
        @if($hd_data->order_id && ($hd_data->payment_mode == 'Z' || $hd_data->payment_mode == 'S'))
          <p>Zomato/Swiggy Order Id: <strong>{{ $hd_data->order_id }}</strong></p>
          <p>Otp: <strong>{{ $hd_data->otp }}</strong></p>
        @endif
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
        <tr>
          <th>Item</th>
          <th>Qty/gram</th>
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
            <td>{{ $d_data->item_desc }}</td>
            <td>{{ $d_data->item_qty ? $d_data->item_qty : $d_data->item_gm . ' gram' }}</td>
            <td class="text-end">{{ number_format($d_data->item_rate, 2) }}</td>
            <td class="text-end">{{ number_format($d_data->amount, 2) }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <div class="row">
      <br><small>HSN/SAC: 996331</small>
      <div class="col-12 text-end">
        <div class="total-box">
          @if($hd_data->discount)
            @php
              $discountAmount = ($hd_data->gross_amt * $hd_data->discount) / 100;
              $finalAmount = $hd_data->gross_amt - $discountAmount;
            @endphp
            <p>Total Amount: <strong>₹{{ number_format($itemAmt, 2) }}</strong></p>
            <p>SGST 2.50% <strong>₹{{ round($itemgst/2, 2) }}</strong></p>
            <p>CGST 2.50% <strong>₹{{ round($itemgst/2, 2) }}</strong></p>
            <p>Discount ({{ $hd_data->discount }}%): <strong>− ₹{{ number_format($discountAmount, 2) }}</strong></p>
            <p>Final Amount: <strong>₹{{ number_format($finalAmount, 2) }}</strong></p>
            <p>Paid (Rounded Off): <strong class="paidAmt">₹{{ number_format($finalAmount, 2) }}</strong></p>
          @else
            <p>Total Amount: <strong>₹{{ number_format($itemAmt, 2) }}</strong></p>
            <p>SGST 2.50% <strong>₹{{ round($itemgst/2, 2) }}</strong></p>
            <p>CGST 2.50% <strong>₹{{ round($itemgst/2, 2) }}</strong></p>
            <p>Paid (Rounded Off): <strong class="paidAmt">₹{{ number_format(round($hd_data->paid_amt), 2) }}</strong></p>
          @endif
          <p class="mt-2"><em class="ptext"></em></p>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <p>Thanks for visiting <strong>{{ $rest_data->rest_name }}</strong><br>Have a nice Day!</p>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function numberToWords(paidAmt) {
    const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
      'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function inWords(n) {
      n = parseInt(n);
      if (isNaN(n)) return "Invalid number";

      if (n < 20) return a[n];
      if (n < 100) return b[Math.floor(n / 10)] + (n % 10 ? " " + a[n % 10] : "");
      if (n < 1000) return a[Math.floor(n / 100)] + " Hundred" + (n % 100 ? " " + inWords(n % 100) : "");
      if (n < 100000) return inWords(Math.floor(n / 1000)) + " Thousand" + (n % 1000 ? " " + inWords(n % 1000) : "");
      if (n < 10000000) return inWords(Math.floor(n / 100000)) + " Lakh" + (n % 100000 ? " " + inWords(n % 100000) : "");
      return inWords(Math.floor(n / 10000000)) + " Crore" + (n % 10000000 ? " " + inWords(n % 10000000) : "");
    }

    const wordsAmt = inWords(paidAmt) + " Rupees Only";
    $('.ptext').text(wordsAmt);
  }

  var paidAmt = $('.paidAmt').text().replace(/[\u20b9,]/g, '').trim();
  numberToWords(paidAmt);
</script>
</body>
</html>
