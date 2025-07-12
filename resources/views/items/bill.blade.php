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
