<!DOCTYPE html>
<html>
<head>
    <title>Pay with Razorpay</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<button id="rzp-button1">Pay ₹{{ $amount / 100 }}</button>
<a href="upi://pay?pa=success@razorpay&pn=TestMerchant&am=1&cu=INR">Click here to test UPI App Launch</a>

<script>
var options = {
    "key": "{{ $razorpay_key }}",
    "amount": "{{ $amount }}",
    "currency": "INR",
    "name": "My Store",
    "description": "Order Payment",
    "order_id": "{{ $order_id }}",
    "handler": function (response){
        window.location.href = "/payment-success?payment_id=" + response.razorpay_payment_id;
    },
    "prefill": {
        "name": "Test User",
        "email": "test@example.com",
        "contact": "9999999999"
    },
    "theme": {
        "color": "#3399cc"
    },
    "method": {
        "upi": true // Only show UPI options like PhonePe, Google Pay
    }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>

</body>
</html>
