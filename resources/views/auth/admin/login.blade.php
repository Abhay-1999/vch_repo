<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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

        #otp-keypad button {
            width: 60px;
            height: 60px;
            font-size: 20px;
            border-radius: 10px;
        }
        #otp-keypad #otp-backspace,
        #otp-keypad #otp-clear {
            width: 50px;
            height: 40px;
            font-size: 14px;
        }
        #otp {
            font-size: 24px;
            letter-spacing: 10px;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-3">
            <img src="{{ asset('images/vijaychat.webp') }}" alt="Logo" style="max-width: 150px;">
        </div>
        <form id="otpLoginForm">
        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="mb-3 mt-2">
            <label class="mb-1">User Name</label>
            <select name="userid" id="userid" class="form-control">
                @foreach($adminData as $d)
                    <option value="{{ $d->userid }}">{{ $d->userid }}</option>
                @endforeach
            </select>
        </div>

        <!-- Centered Button -->
        <div class="d-flex justify-content-center mb-3">
            <button type="button" id="sendOtpBtn" class="btn btn-warning">Send OTP</button>
        </div>


        <div class="mt-3 d-none" id="otpSection">
            <label class="mb-1">Enter OTP</label>
            <input type="text" id="otp" class="form-control text-center" maxlength="4">

            <!-- Custom Numeric Keypad -->
            <div id="otp-keypad" class="mt-3 d-flex flex-wrap justify-content-center">
                @for ($i = 1; $i <= 9; $i++)
                    <button type="button" class="btn btn-light m-1 otp-key" data-key="{{ $i }}">{{ $i }}</button>
                @endfor
                <button type="button" class="btn btn-light m-1 otp-key" data-key="0">0</button>
                <button type="button" class="btn btn-sm btn-danger m-1" id="otp-backspace">⌫</button>
                <button type="button" class="btn btn-sm btn-secondary m-1" id="otp-clear">Clear</button>
            </div>
        </div>

    </form>

<!-- Error message container -->
<div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- <script>
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
</script> -->
<script>
   let selectedUser = null;

    // Enable button only after selecting user
    $('#userid').on('change', function () {
        selectedUser = $(this).val();
        if (selectedUser) {
            $('#sendOtpBtn').prop('disabled', false);
        } else {
            $('#sendOtpBtn').prop('disabled', true);
        }
    });

    $('#sendOtpBtn').click(function () {
        if (!selectedUser) {
            toastr.error("Please select a user first.");
            return;
        }

        $.ajax({
            url: '{{ url("admin/send-otp-login") }}',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                username: selectedUser
            },
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    $('#otpSection').removeClass('d-none');
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error("Failed to send OTP. Please try again.");
            }
        });
    });

    $(document).ready(function () {
        // Handle number button clicks
        $('.otp-key').click(function () {
            let digit = $(this).data('key');
            let current = $('#otp').val();

            if (current.length < 4) {
                $('#otp').val(current + digit).trigger('input'); // trigger input for auto verify
            }
        });

        $('#otp-backspace').click(function () {
            let current = $('#otp').val();
            $('#otp').val(current.slice(0, -1)).trigger('input');
        });

        $('#otp-clear').click(function () {
            $('#otp').val('').trigger('input');
        });

        // Auto verify when 4 digits entered
        $('#otp').on('input', function () {
            const otp = $(this).val();
            if (otp.length === 4) {
                $.ajax({
                    url: '{{ url("admin/verify-otp-login") }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        otp: otp
                    },
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(() => {
                                window.location.href = res.redirect;
                            }, 1000);
                        } else {
                            toastr.error(res.message);
                            $('#otp').val('');
                        }
                    },
                    error: function () {
                        toastr.error("OTP verification failed. Please try again.");
                    }
                });
            }
        });
    });

</script>
</body>
</html>