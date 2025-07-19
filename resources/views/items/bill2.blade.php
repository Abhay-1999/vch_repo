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

    @media (max-width: 576px) {
      .invoice-header {
        font-size: 16px;
      }

      .invoice-box {
        padding: 15px;
      }

      .table-responsive {
        font-size: 13px;
      }
    }
  </style>
</head>
<body>
  <div class="container page-break">
    <div class="invoice-box">
    <div class="d-flex justify-content-between align-items-start mb-3" style="flex-wrap: nowrap;">
  <!-- Left: Restaurant Address -->
  <div style="min-width: 280px; max-width: 60%;">
    <h4 class="invoice-header mb-1">
      {{ $rest_data->rest_name }}
    </h4>
    <p class="mb-1">
      {{ $rest_data->rest_add2 }} {{ $rest_data->rest_city }}<br>
      Phone: 91 {{ $rest_data->rest_contact }}<br>
      GSTIN: {{ $rest_data->rest_gstin }}<br>
      FSSAI: {{ $rest_data->rest_fssai }}<br>
      Website: {{ $rest_data->rest_website }}
    </p>
  </div>

  <!-- Right: Logo -->
  <div class="text-end" style="min-width: 120px;">
    <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="max-height: 80px;">
  </div>
</div>
      <h5 class="text-center">RETAIL INVOICE</h5>
      <div class="d-flex justify-content-between mb-3 flex-wrap">
  <!-- Left side: Invoice No and Invoice Date -->
  <div class="pe-3" style="flex: 1;">
    <p>Invoice No: <strong>{{ date('Y')}}/000000{{ $hd_data->invoice_no }}</strong></p>
    <p>Invoice Date: <strong>{{ date('d F Y', strtotime($hd_data->invoice_date)) }}</strong></p>
  </div>

  <!-- Right side: Token No and Order ID -->
  <div class="text-end ps-3" style="flex: 1;">
    <p>Token No: <strong>{{ $hd_data->tran_no }}</strong></p>
    @if($hd_data->order_id)
      <p>Zomato/Swiggy Order Id: <strong>{{ $hd_data->order_id }}</strong></p>
    @endif
  </div>
</div>



      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Item</th>
              <th>Qty/gram</th>
              <th style="text-align: right;">Rate</th>
              <th style="text-align: right;">Amt</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $itemgst = $itemAmt = 0;
            ?>
            @foreach($dt_data as $d_data)
            <?php $itemgst += $d_data->item_gst; ?>
            <?php $itemAmt += $d_data->amount; ?>
            <tr>
              <td>{{ $d_data->item_desc }}</td>
              <td> 
                @if($d_data->item_qty)
                {{ $d_data->item_qty }}
                @else
                {{ $d_data->item_gm }} gram
                @endif
              <td style="text-align: right;">{{ number_format($d_data->item_rate,2) }} </td>
              <td style="text-align: right;">
                {{ $d_data->amount }}
                </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="row">
      <br><small style="text-align: left;">HSN/SAC: 996331</small>
      <div class="col-12 text-end">
          <div class="total-box">
             
              @if($hd_data->discount)
                  @php
                      $discountAmount = ($hd_data->gross_amt * $hd_data->discount) / 100;
                      $finalAmount = $hd_data->gross_amt - $discountAmount;
                  @endphp
                

                  <p>Total Amount: <strong>₹{{ number_format($itemAmt,2) }}</strong></p>
                  <p>SGST 2.50% <strong>₹{{ round($itemgst/2,2) }}</strong></p>
                  <p>CGST 2.50% <strong>₹{{  round($itemgst/2,2) }}</strong></p>
                  <p>Discount ({{ $hd_data->discount }}%): <strong>− ₹{{ number_format($discountAmount, 2) }}</strong></p>
                  <p>Final Amount: <strong>₹{{ number_format($finalAmount, 2) }}</strong></p>
                  <p>Paid (Rounded Off): <strong>₹</strong><strong class="paidAmt">{{ number_format(round($finalAmount), 2) }}</strong></p>

              @else
                  <p>Total Amount: <strong>₹{{ number_format($itemAmt,2) }}</strong></p>
                  <p>SGST 2.50% <strong>₹{{ round($itemgst/2,2) }}</strong></p>
                  <p>CGST 2.50% <strong>₹{{  round($itemgst/2,2) }}</strong></p>
                  <p>Paid (Rounded Off): <strong>₹</strong> <strong class="paidAmt">{{ number_format(round($hd_data->paid_amt), 2) }}</strong></p>
              @endif

              <p class="mt-2"><em class="ptext"></em></p>
          </div>
      </div>

      </div>

      <div class="text-center mt-4">
        <p>Thanks for visiting <strong>{{ $rest_data->rest_name }}</strong><br>Have a nice Day!</p>
        <!-- <p class="text-muted"><small>For any queries call {{ $rest_data->rest_tellno }}<br>Between 10:00 and 22:00</small></p> -->
      </div>
    </div>
  </div>

  <!-- jQuery + Words -->
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

    var paidAmt = $('.paidAmt').text().trim();
    numberToWords(paidAmt);
  </script>
</body>
</html>
