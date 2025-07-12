<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full height of the viewport */
            background-color:#f38534; !important;
        }
        .login-container {
            width: 100%;
            max-width: 400px; /* Set a max width for the form */
            padding: 20px;
            background-color: white; /* White background for the form */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        button.btn.btn-primary.w-100 {
    background-color: #ff5700d6 !important;
}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-3">
            <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="max-width: 150px;">
        </div>
        <form id="adminLoginForm">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100" id="loginBtn">Login</button>
</form>

<!-- Error message container -->
<div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Set CSRF token in AJAX headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $('#adminLoginForm').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            email: $('input[name="email"]').val(),
            password: $('input[name="password"]').val()
        };

        $('#loginBtn').prop('disabled', true).text('Logging in...');

        $.post("{{ url('admin/login/submitt') }}", formData)
            .done(function(response) {
                window.location.href = response.redirect_url || "{{ url('/admin/dashboard') }}";
            })
            .fail(function(xhr) {
                $('#loginBtn').prop('disabled', false).text('Login');

                let msg = 'Something went wrong.';
                if (xhr.status === 422) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.status === 419) {
                    msg = 'Session expired. Please refresh the page.';
                }

                $('#errorMessage').removeClass('d-none').html(msg);
            });
    });
</script>

</body>
</html>