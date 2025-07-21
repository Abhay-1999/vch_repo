@extends('auth.layouts.app')
@section('content')
<div class="container" id="print-content">
<style>
    @media print {
        #printButton, #exportButton {
            display: none;
        }
        table#myTable {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            border: 1px solid black;
        }
        thead {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            padding-left: 10px;
        }
        tbody td, thead th {
            border: 1px solid black;
            padding-left: 10px;
            line-height: 1.3;
        }
        table#firstTable {
            border: none;
        }
        table#firstTable td, table#firstTable th {
            border: none !important;
        }
        .text-right{
            padding-right:5px;
        }
        .text-left{
            text-align:left;
        }
        th{
            font-size:12px
        }
        td{
            font-size:10px
        }        
        .printDate{
            padding-top:0;
        }
        h2{
            margin-bottom:0;
        }
        .total-row-on-last-page {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        tr, td, th {
            page-break-inside: avoid !important;
        }
    }
    @page {
            font-size:12px;
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
            }
        }

        @media print {
            body {
                counter-reset: page;
            }

            .page-number:after {
                content: "Page " counter(page) " of " counter(pages);
                position: fixed;
            }
        }
        .text-right{
            text-align:right;
        }

        th {
            vertical-align: top;     
            white-space: nowrap;       
            font-size: 14px;        
        }
</style>
<table style="width: 100%; border-collapse: collapse;" id="firstTable">
    <tr>
        <td>
            <h2>BILL-ITEM WISE RECORD</h2>
        </td>
        <td style="text-align: right;">
            <button id="exportButton" onclick="exportToExcel()" class="btn btn-info btn-sm">EXCEL</button>
        </td>
    </tr>
    <tr>
        <td>
            <p class="printDate">FROM : {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>
        </td>
        <td style="text-align: right;">
            <button id="printButton" onclick="printContent()" class="btn btn-primary btn-sm">PRINT</button>
        </td>
    </tr>

</table>

<table class="table table-bordered mt-3" id="myTable">
    <thead>
        <tr>
            <th class="text-left">S.NO</th>
            <th class="text-left">DATE</th>
            <th class="text-left">BILL NO</th>
            <th class="text-left">ITEM</th>
            <th class="text-left">HSN CODE</th>
            <th class="text-right">QTY</th>
            <th class="text-right">RATE</th>
            <th class="text-right">GST %</th>
            <th class="text-right">PAYMENT MODE</th>
            <th class="text-right">GROSS AMT</th>
            <th class="text-right">CGST AMT</th>
            <th class="text-right">SGST AMT</th>
            <th class="text-right">NET AMT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $rateTot = 0;
            $grossTot = 0;
            $cgstTot = 0;
            $sgstTot = 0;
            $grandTot = 0;
        @endphp

        @forelse($data as $index => $d)
            @php
                $cgst = $d->item_gst / 2;
                $sgst = $d->item_gst / 2;
                $total = $d->amount + $d->item_gst;

                $rateTot += $d->amount;
                $grossTot += $d->amount;
                $cgstTot += $cgst;
                $sgstTot += $sgst;
                $grandTot += $total;
            @endphp
        <tr>
            <td class="text-center">{{ ++$index }}</td>
            <td>{{ date('d-m-Y', strtotime($d->tran_date)) ?? '' }}</td>
            <td>{{ $d->tran_no }}</td>
            <td>{{ $d->item_desc }}</td>
            <td>996331</td>
            <td>{{ $d->item_qty }}</td>
            <td class="text-right">{{ number_format($d->amount, 2) }}</td>
            <td>5</td>
            <td>
                @if($d->payment_mode == 'C')
                    Over Counter
                @elseif($d->payment_mode == 'O')
                    UPI
                @else
                    -
                @endif
            </td>
            <td class="text-right">{{ number_format($d->amount, 2) }}</td>
            <td class="text-right">{{ number_format($cgst, 2) }}</td>
            <td class="text-right">{{ number_format($sgst, 2) }}</td>
            <td class="text-right">{{ number_format($total, 2) }}</td>
        </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center text-danger">No records found</td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <td colspan="6" class="text-right"><strong>Total</strong></td>
            <td class="text-right"><strong>{{ number_format($rateTot, 2) }}</strong></td>
            <td></td>
            <td></td>
            <td class="text-right"><strong>{{ number_format($grossTot, 2) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($cgstTot, 2) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($sgstTot, 2) }}</strong></td>
            <td class="text-right"><strong>{{ number_format($grandTot, 2) }}</strong></td>
        </tr>
    </tfoot>

</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
    function exportToExcel() {
        var table = document.getElementById('myTable');
        
        for (var i = 1; i < table.rows.length; i++) { 
            var dateCell = table.rows[i].cells[1]; // Assuming date is in the 2nd column
            dateCell.textContent = formatDate(dateCell.textContent);
        }

        var wb = XLSX.utils.table_to_book(table, {sheet: "Sheet JS"});
        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }

        var fileName = prompt("Enter file name:", "bill_item_wise_report.xlsx");
        if (fileName === null) {
            return; 
        }

        if (!fileName.endsWith('.xlsx')) {
            fileName += '.xlsx';
        }

        var blob = new Blob([s2ab(wbout)], {type:"application/octet-stream"});
        saveAs(blob, fileName);
    }

    // Helper function to format dates in d-m-Y format
    function formatDate(dateStr) {
        var parts = dateStr.split('-');
        if (parts.length === 3) {
            return parts[1] + '-' + parts[0] + '-' + parts[2]; // Convert to m-d-Y
        }
        return dateStr;
    }

    function printContent() {
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; }');
        printWindow.document.write('@media print { #printButtonDiv { display: none; } }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(document.getElementById('print-content').innerHTML);
        printWindow.document.close();
        printWindow.print();
    }
</script>
</div>
@endsection