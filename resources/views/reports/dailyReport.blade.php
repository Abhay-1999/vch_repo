<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 15px;
            color: #000;
            margin: 20px 40px;
        }

        table {
            border-collapse: collapse;
            width: 60%;
        }

        th, td {
            border: 1px solid #000;
            padding: 10px 12px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 16px;
        }

        td {
            font-size: 15px;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .no-border td {
            border: none !important;
        }

        h1, h3 {
            text-align: center;
            margin: 5px 0;
        }

        h1 {
            font-size: 26px;
            font-weight: bold;
        }

        h3 {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .logo {
            max-height: 70px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .report-header {
            margin-bottom: 20px;
        }

        .summary-table {
            margin-top: 20px;
        }

        tfoot td {
            font-weight: bold;
            background-color: #fafafa;
            font-size: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 13px;
            color: #555;
        }

        @page {
            margin: 30px 40px;
        }
    </style>
</head>
<body>

    <!-- Logo + Title -->
    <div class="report-header">
        <img src="{{ public_path('images/vijaychat.webp') }}" class="logo" alt="Logo">
        <h1>VIJAY CHAT HOUSE</h1>
        <h3>GSTIN: 23AAFFV8652G1Z0</h3>
    </div>


    <!-- Main Table -->
    <table style="margin: 0 auto; border-collapse: collapse; width: 80%; font-size: 16px; text-align: center;" border="1" cellpadding="8">        <thead>
            <tr>
                <th style="width: 10%;">S.No</th>
                <th style="width: 45%;">Date</th>
                <th style="width: 45%;">Online Sales (₹)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $i = 1; 
                $grandTotalOnline = 0;
            @endphp
            @foreach ($totals as $date => $amounts)
                @php $rowTotal = $amounts['Online']; @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td class="text-center">{{ $date }}</td>
                    <td class="text-center">{{ number_format($amounts['Online'], 2) }}</td>
                </tr>
                @php $grandTotalOnline += $amounts['Online']; @endphp
            @endforeach
        </tbody>

        <tfoot>
            @php
                $baseOnline = $grandTotalOnline / 1.05;
                $cgstOnline = $baseOnline * 0.025;
                $sgstOnline = $cgstOnline;
            @endphp

            <tr>
                <td colspan="2" class="text-righttext-center">Total (Excl. GST)</td>
                <td class="text-center">{{ number_format($baseOnline, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">CGST (2.5%)</td>
                <td class="text-center">{{ number_format($cgstOnline, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">SGST (2.5%)</td>
                <td class="text-center">{{ number_format($sgstOnline, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">Total (Inclusive GST)</td>
                <td class="text-center">{{ number_format($grandTotalOnline, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Report generated on {{ \Carbon\Carbon::now('Asia/Kolkata')->format('d-m-Y h:i A') }}
    </div>

</body>
</html>
