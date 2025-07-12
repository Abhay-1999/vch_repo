<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Test Payment Gateway</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f4f4f4;
      text-align: center;
      padding-top: 50px;
    }
    img {
      max-width: 100%;
      height: auto;
    }
    .btn-pay {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Payment Gateway</h2>
    <p>This is a dummy payment gateway for demo purposes.</p>
    <img src="{{ asset('images/payment.png') }}" alt="Payment Gateway Simulation" />
    <div class="btn-pay">
      <a href="{{ route('items.checkout') }}" class="btn btn-success">Simulate Payment Success</a>
    </div>
  </div>
</body>
</html>
