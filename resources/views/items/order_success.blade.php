<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <meta http-equiv="refresh" content="5;url={{ route('items.index') }}" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            margin: 0;
            background-color: #fffaf0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .message {
            font-size: 48px;
            font-weight: bold;
            color: #28a745;
        }
        .subtext {
            font-size: 22px;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div>
        <div class="message">Thank You for Your Order!</div>
        <div class="subtext">You can collect your food at the counter.</div>
    </div>
</body>
</html>
