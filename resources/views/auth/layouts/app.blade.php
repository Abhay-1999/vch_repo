<?php
use Illuminate\Support\Facades\Auth;
$admin = Auth::guard('admin')->user();

        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $admin->name }} Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }} />
	<!-- font family Dm  -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
		rel="stylesheet">
	<!-- aos css -->
	<link rel="stylesheet" href="{{ asset('assets/aos/aos.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
        body {
            background-color: #f1f4f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px 15px;
            flex-shrink: 0;
        }

        .sidebar h4 {
            color: #fff;
            margin-bottom: 30px;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            font-weight: 500;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #fff;
        }

        .main-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background-color: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            flex-shrink: 0;
        }

        .content {
            padding: 20px 30px;
            overflow-y: auto;
            height: calc(100vh - 70px); /* Adjust height based on topbar */
        }

        .card {
            border-radius: 10px;
        }

        .logout-btn {
            background-color: #dc3545;
            border: none;
        }

         /* Loader Styles */
        #loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(8, 8, 8, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none; /* Hidden by default */
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4><i class="bi bi-speedometer2 me-2"></i>{{ $admin->name }} Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    @if($admin->role == '3')
                    <a class="nav-link {{ request()->routeIs('orders.indexp') ? 'active' : '' }}" href="{{ route('orders.indexp') }}">
                        <i class="bi bi-bag-fill me-2"></i>Pending Order
                    </a>
                    <a class="nav-link {{ request()->routeIs('create.order') ? 'active' : '' }}" href="{{ route('create.order') }}">
                        <i class="bi bi-bag-fill me-2"></i>Create Order
                    </a>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center"
                        href="#" id="dropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span><i class="bi bi-graph-up-arrow me-2"></i>Reports</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownReports">
                            <li>
                                <a class="dropdown-item" href="{{ route('item_ws_form') }}">
                                    <i class="bi bi-file-earmark-text me-2"></i>Item Wise
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('bill_ws_form') }}">
                                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Bill Wise
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('mode_pay_form') }}">
                                    <i class="bi bi-file-earmark-bar-graph me-2"></i>Mode Wise Payment
                                </a>
                            </li>
                        </ul>
                    </li>

                    @else
                    <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="bi bi-bag-fill me-2"></i>Orders
                    </a>
                    @endif
                </li>
                <li class="nav-item mt-4">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main area (Topbar + Content) -->
        <div class="main-area">
            <div class="topbar d-flex">
                    <h5>Welcome, {{ $admin->name }}</h5>
                    <h6 class="ms-auto text-right black" id="datetime"></h6>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Loader Overlay -->
    <div id="loader-overlay">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/js/bootstrap.bundle.min.js"></script>
    
	<!-- aos animation -->
	<script src="{{ asset('assets/aos/aos.js') }}"></script>
	<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>
  function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        document.getElementById('datetime').innerHTML = now.toLocaleString('en-US', options);
    }

    // Update the date and time every second
    setInterval(updateDateTime, 1000);

    // Initial call to display the date and time immediately
    updateDateTime();

    // Function to show the loader
        function showLoader() {
            document.getElementById('loader-overlay').style.display = 'flex';
        }
        // Function to hide the loader
        function hideLoader() {
            document.getElementById('loader-overlay').style.display = 'none';
        }
        // Attach the showLoader function to all forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', showLoader);
        });
</script>
</body>
</html>
