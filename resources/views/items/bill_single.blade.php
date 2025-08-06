<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
    /* Reset default margins/padding */
    html, body {
      margin: 0;
      padding: 0;
      background: #fff;
      text-align: center;
    }

    /* Container sized for 80mm thermal paper */
    .token-container {
      width: 80mm;
      margin: 0 auto;
      padding-top: 10mm; /* small spacing at top if desired */
    }

    /* Token styling */
    .token-box {
      font-size: 20px;
      font-weight: bold;
      border: 2px dashed #000;
      display: inline-block;
      padding: 4px 12px;
      margin: 0;
    }

    @media print {
      /* Remove all extra whitespace */
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
            font-size: 14px; /* Optional: adjust size for thermal printer */
            padding: 6px 4px;
          }
      
  
  
   @page {
        size: 80mm auto;
        margin: 0;
      }
    
      body {
        margin: 0 !important;
        padding: 0 !important;
      }

    }
   

  </style>
</head>
<body>

  <div class="container page-break" id="invoiceContent">
    <div class="invoice-box p-4">
      
      <div class="token-container">
    <div class="token-box">
      Token No: <strong>{{ $hd_data->tran_no }}</strong>
    </div>
  </div>

      <!-- Payment and Time -->
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

      <!-- Item Table -->
      <table class="table table-bordered">
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

      <!-- Total Section -->
      <div class="text-end">
        @if($hd_data->discount)
          @php
              $discountAmount = ($hd_data->gross_amt * $hd_data->discount) / 100;
              $finalAmount = $hd_data->gross_amt - $discountAmount;
          @endphp
          <p class="total-amount">Gross: ₹{{ number_format($hd_data->gross_amt, 2) }}</p>
          <p class="total-amount">Discount ({{ $hd_data->discount }}%): − ₹{{ number_format($discountAmount, 2) }}</p>
          <p class="total-amount fw-bold">Final: ₹{{ number_format($finalAmount, 2) }}</p>
        @else
          <p class="total-amount">Total: ₹{{ number_format(round($hd_data->paid_amt), 2) }}</p>
        @endif
      </div>
    </div>
  </div>

  <!-- Buttons -->
  <div class="text-center mt-4">
    <button class="btn btn-success" onclick="saveInvoiceAsImage()">Save as Image</button>
  </div>

  <!-- html2canvas script -->
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
