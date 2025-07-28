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
            <h4 style="margin: 0;">ITEM WISE SALE SUMMARY</h4>
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
            <th class="text-left">ITEM NAME</th>
            <th class="text-left">CATEGORY</th>
            <th class="text-center">QTY SOLD</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totQty = 0;
        @endphp
        @foreach($data as $index => $d)
        <tr>
            <td class="text-center">{{ ++$index }}</td>
            <td>{{ $d->item_desc }}</td>
            <td>{{ $d->item_grpdesc }}</td>
            <td class="text-center">
                @if($d->total_qty > 0)
                    {{ $d->total_qty }}
                @elseif($d->total_gm_qty > 0)
                    {{ $d->total_gm_qty }} Kg
                @else
                    0
                @endif
            </td>

        </tr>
        @php
            $totQty += $d->total_qty;
        @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right"><strong>Total</strong></td>
            <td class="text-center"><strong>{{ $totQty }}</strong></td>
        </tr>
    </tfoot>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
    function exportToExcel() {
        var table = document.getElementById('myTable');
        
        for (var i = 1; i < table.rows.length; i++) { 
            var dateCell = table.rows[i].cells[2]; // Assuming date is in the 2nd column
        }

        var wb = XLSX.utils.table_to_book(table, {sheet: "Sheet JS"});
        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }

        var fileName = prompt("Enter file name:", "item_wise_sale_report.xlsx");
        if (fileName === null) {
            return; 
        }

        if (!fileName.endsWith('.xlsx')) {
            fileName += '.xlsx';
        }

        var blob = new Blob([s2ab(wbout)], {type:"application/octet-stream"});
        saveAs(blob, fileName);
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