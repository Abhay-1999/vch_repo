<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tax Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      padding: 15px;
    }
    .invoice-box {
      /* max-width: 100%; */
      margin: auto;
      border: 1px solid #ddd;
      padding: 20px;
      border-radius: 10px;
    }
    .invoice-title {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .logo {
      max-height: 50px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .text-small {
      font-size: 0.9rem;
    }
    @media (max-width: 576px) {
      .invoice-title {
        font-size: 1.2rem;
      }
      .table-responsive {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<div class="invoice-box">
  <div class="row align-items-center invoice-header mb-3 border-bottom pb-2">
    <div class="col-12 col-md-8">
      <div class="invoice-title">Tax Invoice</div>
      <p class="mb-1"><strong>Address:</strong> Chamunda Complex, 2nd Floor, AB Rd, Dewas, Madhya Pradesh 455001</p>
      <p class="mb-1"><strong>GST:</strong> 09AACCR7700E1ZD</p>
      <p class="mb-1"><strong>PAN:</strong> AACCR7700E</p>
      <p class="mb-1"><strong>CIN:</strong> U72900UP2019PTC120375</p>
    </div>
    <div class="col-12 col-md-4 text-md-end text-center mt-3 mt-md-0">
      <img src="{{ asset('images/rest_logo.jpg') }}" class="logo" alt="DirectDine Logo">
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered mb-4">
      <thead class="table-light">
        <tr>
          <th>Description</th>
          <th>Rate</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Convenience Charge</td>
          <td></td>
          <td>{{ number_format($convi_fee1, 2) }}</td>
        </tr>
        <tr>
          <td>IGST</td>
          <td>18%</td>
          <td>{{ number_format($convi_fee_gst, 2) }}</td>
        </tr>
        <tr>
          <td colspan="2"><strong>Total Amount</strong></td>
          <td><strong class="paidAmtfinal">
            {{ number_format($final_conv, 2) }}
          </strong></td>
        </tr>
      </tbody>
    </table>
  </div>

  <p><strong>Total Amount in Words:</strong> <strong class="ptextfinal">Thirty Five Rupees and Twenty Two Paisa only</strong></p>

  <p class="mt-4"><strong>Reg. Address:</strong> {{ $rest_data->rest_add }} {{ $rest_data->rest_add2 }} {{ $rest_data->rest_city }}</p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function numberToWords(paidAmt) {
    const a = [
      '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
      'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen',
      'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    ];
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

    var wordsAmt = inWords(paidAmt) + " Rupees Only";
    $('.ptextfinal').text(wordsAmt);
  }

  var paidAmt = $('.paidAmtfinal').text().trim();
  numberToWords(paidAmt);
</script>
</body>
</html>
