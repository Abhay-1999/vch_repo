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
            font-size:13px
        }
        td{
            font-size:11px
        }
        tfoot {
            border-top: 1px solid black;
            border-bottom: 1px solid black; 
        }

        tfoot td {
            border: 1px solid black;
        }
        .printDate{
            padding-top:0;
        }
        h2{
            margin-bottom:0;
        }
        .printHead{
            padding-left:50px;
        }
    }
    @page {
            font-size:12px;
            margin-top:0px;
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                margin-top:-50px;
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
</style>
<table id="firstTable" style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
    <tr>
        <td style="width: 100px;">
            <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="max-height: 50px;">
        </td>
        <td style="padding-left: 30px; vertical-align: middle;">
            <h2 style="margin: 0;" class="printHead">
                VIJAY CHAT HOUSE - GSTIN: 23AAFFV8652G1Z0
            </h2>
        </td>
        <td style="text-align: right;">
            <button id="exportButton" onclick="exportToExcel()" class="btn btn-info btn-sm mb-1">EXCEL</button><br>
            <button id="printButton" onclick="printContent()" class="btn btn-primary btn-sm">PRINT</button>
        </td>
    </tr>
</table>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px;" id="firstTable">
    <tr>
        <td style="text-align: left; vertical-align: top;">
            <h4 style="margin: 0;">DATE WISE SALES SUMMARY</h4>
        </td>
        <td style="text-align: right; vertical-align: top;">
            <p class="printDate" style="margin: 0;">
                FROM : {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
            </p>
        </td>
    </tr>
</table>

<table class="table table-bordered mt-3" id="myTable">
    <thead>
        <tr>
            <th class="text-left">S.No</th>
            <th class="text-left">Date</th>
            <th class="text-right">Cash</th>
            <th class="text-right">Online</th>
            <th class="text-right">UPI</th>
            <th class="text-right">Zomato</th>
            <th class="text-right">Swiggy</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i = 1; 
            $grandTotalCash = 0; 
            $grandTotalOnline = 0;   
            $grandTotalUPI = 0;   
            $grandTotalZomato = 0;
            $grandTotalSwiggy = 0;
        @endphp
        @foreach ($totals as $date => $amounts)
        <tr>
            <td class="text-left">{{ $i++ }}</td>
            <td class="text-left">{{ $date }}</td>
            <td class="text-right">{{ number_format($amounts['cash'], 2) }}</td>
            <td class="text-right">{{ number_format($amounts['Online'], 2) }}</td>
            <td class="text-right">{{ number_format($amounts['UPI'], 2) }}</td>
            <td class="text-right">{{ number_format($amounts['Zomato'], 2) }}</td>
            <td class="text-right">{{ number_format($amounts['Swiggy'], 2) }}</td>
        </tr>
        @php
            $grandTotalCash += $amounts['cash'];
            $grandTotalOnline += $amounts['Online'];
            $grandTotalUPI += $amounts['UPI'];
            $grandTotalZomato += $amounts['Zomato'];
            $grandTotalSwiggy += $amounts['Swiggy'];
        @endphp
        @endforeach
    </tbody>
    <tfoot>

    @php
        // GST Breakdown
        $baseCash = $grandTotalCash / 1.05;
        $baseOnline = $grandTotalOnline / 1.05;
        $baseUPI = $grandTotalUPI / 1.05;
        $baseZomato = $grandTotalZomato / 1.05;
        $baseSwiggy = $grandTotalSwiggy / 1.05;

        $cgstCash = $baseCash * 0.025;
        $cgstOnline = $baseOnline * 0.025;
        $cgstUPI = $baseUPI * 0.025;
        $cgstZomato = $baseZomato * 0.025;
        $cgstSwiggy = $baseSwiggy * 0.025;

        $sgstCash = $cgstCash;
        $sgstOnline = $cgstOnline;
        $sgstUPI = $cgstUPI;
        $sgstZomato = $cgstZomato;
        $sgstSwiggy = $cgstSwiggy;
    @endphp

    <tr>
        <td colspan="2" class="text-right"><strong>Total (Excl. GST)</strong></td>
        <td class="text-right"><strong>{{ number_format($baseCash, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($baseOnline, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($baseUPI, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($baseZomato, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($baseSwiggy, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="2" class="text-right"><strong>CGST (2.5%)</strong></td>
        <td class="text-right"><strong>{{ number_format($cgstCash, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($cgstOnline, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($cgstUPI, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($cgstZomato, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($cgstSwiggy, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="2" class="text-right"><strong>SGST (2.5%)</strong></td>
        <td class="text-right"><strong>{{ number_format($sgstCash, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($sgstOnline, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($sgstUPI, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($sgstZomato, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($sgstSwiggy, 2) }}</strong></td>
    </tr>
    <tr>
        <td colspan="2" class="text-right"><strong>Total (Inclusive GST)</strong></td>
        <td class="text-right"><strong>{{ number_format($grandTotalCash, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($grandTotalOnline, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($grandTotalUPI, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($grandTotalZomato, 2) }}</strong></td>
        <td class="text-right"><strong>{{ number_format($grandTotalSwiggy, 2) }}</strong></td>
    </tr>
</tfoot>

</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
    function exportToExcel() {
        var table = document.getElementById('myTable');
        
        for (var i = 1; i < table.rows.length; i++) { 
            var dateCell = table.rows[i].cells[1];
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

        var fileName = prompt("Enter file name:", "date_wise_sale_summary.xlsx");
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
    printWindow.document.write('@media print {');
    printWindow.document.write('  @page { size: landscape; }'); // 👈 Forces landscape orientation
    printWindow.document.write('  #printButtonDiv { display: none; }');
    printWindow.document.write('}');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(document.getElementById('print-content').innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Wait for content to load before printing
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}

</script>
</div>
@endsection