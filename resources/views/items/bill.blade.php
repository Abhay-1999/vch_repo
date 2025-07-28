<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice</title>
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
    }

    .invoice-header {
      font-size: 20px;
      font-weight: bold;
    }

    .total-amount {
      font-size: 18px;
      font-weight: bold;
    }

    @media print {
      body {
        background-color: #fff;
        margin: 0;
        padding: 0;
      }

      .invoice-box {
        box-shadow: none;
        border-radius: 0;
        padding: 10px;
        font-size: 12px;
        width: 80mm; /* for thermal printer */
      }

      .btn {
        display: none;
      }

      .table th, .table td {
        padding: 4px;
      }

      .total-amount {
        font-size: 14px;
      }
    }

    @media (max-width: 576px) {
      .invoice-box {
        padding: 15px;
      }

      .invoice-header {
        font-size: 18px;
      }

      .total-amount {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <div class="container page-break" id="invoiceContent">
    <div class="invoice-box p-4">
      <div class="row mb-3 align-items-center">
        <div class="col-6 d-flex align-items-center">
          <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="height: 40px; margin-right: 10px;">
          <h5 class="invoice-header mb-0">Vijay Chaat House</h5>
        </div>
        <div class="col-6 text-end">
          <p>Token No: <strong>{{ $hd_data->tran_no }}</strong></p>
          <p>Payment Mode: <strong>{{ $paymentMode }}</strong></p>
          <p>Order Time: <strong>{{ date('d M Y, h:i A', strtotime($hd_data->created_at)) }}</strong></p>
        </div>
      </div>

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
            <?php $itemAmt = $d_data->amount + $d_data->item_gst; ?>
            <td>{{ $d_data->item_desc }}</td>
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
