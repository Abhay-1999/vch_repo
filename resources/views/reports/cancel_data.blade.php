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
        /* table#firstTable, 
        table#firstTable tr,
        table#firstTable td,
        table#firstTable th {
            border: none !important;
        } */
        .printHead{
            padding-left:50px;
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
            <h4 style="margin: 0;">CANCEL SALES REGISTER</h4>
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
            <th class="text-left">S.NO</th>
            <th class="text-left">BILL NO</th>
            <th class="text-left">DATE</th>
            <th class="text-left">TOKEN NO</th>
            <th class="text-left">PAYMENT MODE</th>
            <th class="text-right">AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $index => $d)
        @php
            $fullStr = str_pad($d->invoice_no, 10, '0', STR_PAD_LEFT);
            $prefix = substr($fullStr, 0, 2);
            $branch = substr($fullStr, 2, 2);
            $serial = (int)substr($fullStr, 4);
            $formattedInvoiceNo = $prefix . '-' . $branch . '/' . $serial;
        @endphp
        <tr>
            <td class="text-center">{{ ++$index }}</td>
            <td>{{ $formattedInvoiceNo }}</td>
            <td>{{ date('d-m-Y', strtotime($d->tran_date)) ?? '' }}</td>
            <td>{{ $d->tran_no }}</td>
            <td>
                @if($d->payment_mode == 'C')
                    Cash
                @elseif($d->payment_mode == 'O')
                    Online
                @elseif($d->payment_mode == 'U')
                    Counter UPI
                @elseif($d->payment_mode == 'Z')
                    Zomato
                @elseif($d->payment_mode == 'S')
                    Swiggy
                @else
                    -
                @endif
            </td>
            <td class="text-right">{{ number_format($d->paid_amt, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-danger">No record found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
    function exportToExcel() {
        var table = document.getElementById('myTable');
        
        for (var i = 1; i < table.rows.length; i++) { 
            var dateCell = table.rows[i].cells[2]; // Assuming date is in the 2nd column
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

        var fileName = prompt("Enter file name:", "sale_register_report.xlsx");
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