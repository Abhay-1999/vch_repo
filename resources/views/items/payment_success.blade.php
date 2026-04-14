<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body style="text-align:center; padding-top:50px;">

    <h2>Payment Successful ✅</h2>

    <video width="300" autoplay muted>
        <source src="{{ asset('success.mp4') }}" type="video/mp4">
    </video>

    <p>Redirecting to home...</p>

    <script>
        setTimeout(function(){
            window.location.href = "{{ url('/') }}";
        }, 3000); // 3 sec
    </script>

</body>
</html>