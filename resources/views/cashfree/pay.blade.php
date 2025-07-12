<!DOCTYPE html>
<html>
<head>
    <title>Cashfree AJAX Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Cashfree Payment Gateway via AJAX</h2>
    <button id="payNowBtn">Pay ₹100</button>

    <script>
        document.getElementById("payNowBtn").addEventListener("click", function () {
            fetch("{{ route('cashfree.create') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "https://sandbox.cashfree.com/pg/view/sessions/checkout/web/" + data.order_token;
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Payment error:", error);
                alert("Something went wrong.");
            });
        });
    </script>
</body>
</html>
