<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Terms & Conditions</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

<!-- Toastr JS -->
  <style>
    body {
      background-image: url('images/directdine.png');
      background-size: cover;
      background-position: center;
      color: #fff;
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
    }

    .header, .footer {
      background-color: rgba(0, 0, 0, 0.7);
      text-align: center;
      padding: 15px;
      position: fixed;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    .header {
      top: 0;
    }

    .footer {
      bottom: 0;
    }

    .content {
      padding-top: 150px;
      padding-bottom: 70px;
      overflow-y: auto;
      height:calc(100vh - 100px)
    }

    .table-container {
      display: none;
    }

    .card-item {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 10px;
      color: #000;
    }

    .card-item .d-flex {
      justify-content: space-between;
      align-items: center;
    }

    @media (min-width: 768px) {
      .table-container {
        display: block;
      }
      h1{
        font-size:15px !important;
      }
      .mobile-cart {
        display: none;
      }
      .content {
      padding-top: 800px !important;
     }
  }

  @media (min-width: 320px) {
      
      .content {
      padding-top: 200px !important;
     }
  }
  </style>
</head>
<body>
<div class="container py-4" >
    <h2 class="mb-3">Terms and Conditions</h2>

    <p>Welcome to our restaurant! Please read the following terms and conditions carefully before placing an order or making a payment online.</p>

    <h5 class="mt-4">1. Online Ordering</h5>
    <ul>
        <li>Orders placed through our website are for dine-in only, unless stated otherwise.</li>
        <li>Please ensure all order details, including selected items and quantities, are correct before proceeding to payment.</li>
    </ul>

    <h5 class="mt-4">2. Payment</h5>
    <ul>
        <li>We accept secure online payments via UPI, credit/debit cards, or other listed gateways.</li>
        <li>All payments must be made in Indian Rupees (INR).</li>
        <li>Once payment is completed, you will receive a confirmation of your order via screen and/or SMS.</li>
    </ul>

    <h5 class="mt-4">3. Cancellation & Refund</h5>
    <ul>
        <li>Orders once placed cannot be cancelled online. Please contact our staff for assistance.</li>
        <li>Refunds are issued only in case of payment errors or unavailability of ordered items.</li>
    </ul>

    <h5 class="mt-4">4. OTP Verification</h5>
    <ul>
        <li>For security, mobile OTP verification is required before confirming payment.</li>
        <li>We do not share your personal details with third parties.</li>
    </ul>

    <h5 class="mt-4">5. Privacy</h5>
    <ul>
        <li>Your contact and payment details are encrypted and handled securely.</li>
        <li>We do not store your card information on our servers.</li>
    </ul>

    <h5 class="mt-4">6. Contact</h5>
    <p>If you have questions or issues related to your order or payment, please speak to our staff or call us at <strong>+91-XXXXXXXXXX</strong>.</p>

    <p class="mt-4"><strong>Thank you for choosing us and enjoy your meal!</strong></p>
</div>
</body>
</html>

