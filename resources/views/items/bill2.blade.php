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
    padding: 10px;
    font-size: 14px;
    line-height: 1.15;
  }

  .invoice-box {
    background: #fff;
    padding: 8px;
    border-radius: 0;
    box-shadow: none;
    font-size: 14px;
    width: 72mm;
    margin: auto;
    line-height: 1.15;
  }

  .invoice-header {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 4px;
  }

  p, small {
    margin: 0 0 2px;
    line-height: 1.15;
  }

  .table th, .table td {
    padding: 3px 2px !important;
    vertical-align: middle;
    font-size: 14px !important;
    line-height: 1.15;
  }

  .total-box p {
    margin: 0 0 2px;
    font-size: 14px;
  }

  .text-end {
    text-align: right;
  }

  @media print {
    body {
      margin: 0;
      padding: 0;
      background-color: #fff;
      font-size: 14px;
      line-height: 1.15;
    }

    .invoice-box {
      box-shadow: none;
      border-radius: 0;
      padding: 0;
      font-size: 14px;
      width: 72mm;
    }

    .btn, .no-print {
      display: none !important;
    }
  }
</style>


</head>
<body>
<div class="invoice-box" id="invoiceContent">
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

  @if($cust_hd_data)
    <div class="mb-2">
      <strong>{{ $cust_hd_data->name }}</strong><br>
      {{ $cust_hd_data->address }}<br>
      Phone: +91 {{ $cust_hd_data->mob_no }}<br>
      GSTIN: {{ $cust_hd_data->gst_no }}
    </div>
  @endif

  <div class="text-center mb-2">
    <strong>RETAIL INVOICE</strong>
  </div>

  <div class="mb-2">
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

  <div>
    <small>HSN/SAC: 996331</small>
    <div class="text-end total-box mt-1">
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
      <p class="mt-1"><em class="ptext"></em></p>
    </div>
  </div>

  <div class="text-center mt-2">
    <p>Thanks for visiting <strong>{{ $rest_data->rest_name }}</strong><br>Have a nice day!</p>
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

  var paidAmt = $('.total-box strong').last().text().replace(/[^0-9.]/g, '').trim();
  numberToWords(paidAmt);
</script>
</body>
</html>
