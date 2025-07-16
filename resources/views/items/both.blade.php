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
            font-size: 22px;
            font-weight: bold;
        }
        .pdf-content {
        border: 1px solid #ddd;
        padding: 20px;
        }
     
    </style>
</head>
<body id="invoiceContent">
    <div class="container page-break ">
        <div class="invoice-box p-4">
            <div class="row mb-3">
                <div class="col-6">
                    <h4 class="invoice-header">Vijay Chaat House</h4>
                </div>
                <div class="col-6 text-end">
                    <p>Token No: <strong>{{ $hd_data->tran_no }}</strong></p>
                    <p>Order Time: <strong>{{ date('d F Y, h:i A',strtotime($hd_data->created_at)) }}</strong></p>
                </div>
            </div>
            
           
            
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                 
                    @foreach($dt_data as $d_data)
                    <tr>
                        <?php $itemAmt = $d_data->amount + $d_data->item_gst; ?>
                        <td>{{ $d_data->item_desc }}</td>
                        <td>{{ $d_data->item_qty }}</td>
                        <td>{{ $itemAmt }}</td>
                        <td>{{ $itemAmt*$d_data->item_qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="row text-end">
                <div class="col-12">
              
              
                   
                  
                    <p class="total-amount">Total Amount: ₹ {{ number_format(round($hd_data->paid_amt),2) }}</p>
                </div>
            </div>
        </div>
    </div>


</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
//   window.onload = function () {
//     const element = document.getElementById("invoiceContent");
//     html2pdf()
//       .set({
//         margin: 10,
//         filename: 'invoice.pdf',
//         image: { type: 'jpeg', quality: 0.98 },
//         html2canvas: { scale: 2 },
//         jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
//       })
//       .from(element)
//       .save();
//   };
</script>
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
      <div class="row mb-3">
        <div class="col-12 col-md-6">
          <h4 class="invoice-header">
            {{ $rest_data->rest_name }}<br>
            {{ $rest_data->rest_add2 }} {{ $rest_data->rest_city }}
          </h4>
          <p>
            Phone: 91 {{ $rest_data->rest_contact }}<br>
            GSTIN: {{ $rest_data->rest_gstin }}<br>
            FSSAI: {{ $rest_data->rest_fssai }}<br>
            Regd. Off: Dewas<br>
            Website: {{ $rest_data->rest_website }}
          </p>
        </div>
        <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
          <strong>RESTAURANT SERVICE</strong><br>
          Order ID: <strong>{{ $hd_data->invoice_no }}</strong><br>
          Invoice No: <strong>303/2425/539373</strong><br>
          Date: <strong>{{ date('d-m-Y',strtotime($hd_data->tran_date)) }}</strong><br>
          Customer: void@razorpay.com<br>{{ $hd_data->cust_mobile }}<br>
          Table: <strong>{{ $hd_data->table_no }}</strong>
        </div>
      </div>

      <h5 class="text-center">TAX INVOICE</h5>
      <p>Token No: <strong>{{ rand(12458,74859) }}</strong></p>

      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Description</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Amt</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dt_data as $d_data)
            <?php $itemAmt = $d_data->amount + $d_data->item_gst; ?>
            <tr>
              <td>{{ $d_data->item_desc }}</td>
              <td>{{ $d_data->item_qty }}<br><small>HSN/SAC: 996331</small></td>
              <td>{{ $d_data->amount }}<br><small>{{ $d_data->igst }}% GST</small></td>
              <td>{{ $itemAmt*$d_data->item_qty }}<br><small>{{ $d_data->item_gst }}</small></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="row">
        <div class="col-12 text-end">
          <div class="total-box">
            <hr>
            <p>Total Amount: <strong>{{ number_format(round($hd_data->paid_amt),2) }}</strong></p>
            <p>Paid (Rounded Off): <strong class="paidAmt">{{ number_format(round($hd_data->paid_amt),2) }}</strong></p>
            <p class="mt-2"><em class="ptext"></em></p>
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <p>Thanks for visiting <strong>{{ $rest_data->rest_name }}</strong><br>Have a nice Day!</p>
        <p class="text-muted"><small>For any queries call {{ $rest_data->rest_tellno }}<br>Between 10:00 and 22:00</small></p>
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

