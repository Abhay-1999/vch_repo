<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? 'Daily Report' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .header {
            background: #007BFF;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            font-size: 20px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            font-size: 15px;
            line-height: 1.6;
        }
        .footer {
            background: #f2f2f2;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
      <div class="header">

          Vijay Chat House - Daily Report <strong>{{ \Carbon\Carbon::now('Asia/Kolkata')->format('d-m-Y') }}</strong>.

      </div>

       
        <div class="footer">
            &copy; {{ date('Y') }} Vijay Chat House. All rights reserved.
        </div>
    </div>
</body>
</html>
