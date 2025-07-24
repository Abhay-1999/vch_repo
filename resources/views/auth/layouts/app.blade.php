<?php
use Illuminate\Support\Facades\Auth;
$admin = Auth::guard('admin')->user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $admin->name }} Dashboard</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/aos/aos.css') }}">

  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background-color: #f1f4f9;
      font-family: 'Work Sans', sans-serif;
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
      transition: width 0.3s;
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
      align-items: center;
      height: 70px;
      flex-shrink: 0;
    }

    .content {
      padding: 20px 30px;
      overflow-y: auto;
      height: calc(100vh - 70px);
    }

    .logout-btn {
      background-color: #dc3545;
      border: none;
    }

    /* Sidebar Collapse */
    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar.collapsed h4,
    .sidebar.collapsed .nav-link span {
      display: none;
    }

    .sidebar.collapsed .nav-link i {
      font-size: 1.2rem;
      width: 100%;
      text-align: center;
    }

    /* Loader Overlay */
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
      display: none;
    }
  </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4><i class="bi bi-speedometer2 me-2"></i><span>{{ $admin->name }} Panel</span></h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                @if($admin->role == '3')
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-bag-fill me-2"></i><span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('create.order') ? 'active' : '' }}" href="{{ route('create.order') }}">
                        <i class="bi bi-bag-fill me-2"></i><span>Create Order</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('orders.delivered') ? 'active' : '' }}" href="{{ route('orders.delivered') }}">
                        <i class="bi bi-bag-fill me-2"></i><span>Delivered Order</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('items') ? 'active' : '' }}" href="{{ route('items') }}">
                        <i class="bi bi-bag-fill me-2"></i><span>Items</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('orders.indexp') ? 'active' : '' }}" href="{{ route('orders.indexp') }}">
                        <i class="bi bi-bag-fill me-2"></i><span>Pending Order</span>
                    </a>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center"
                        href="#" id="dropdownReports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span><i class="bi bi-graph-up-arrow me-2"></i><span>Reports</span></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownReports">
                            <li><a class="dropdown-item" href="{{ route('bill_ws_form') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i>Bill Wise</a></li>
                            <li><a class="dropdown-item" href="{{ route('bill_item_form') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i>Bill-Item Wise</a></li>
                            <li><a class="dropdown-item" href="{{ route('item_ws_form') }}"><i class="bi bi-file-earmark-text me-2"></i>Item Wise</a></li>
                            <li><a class="dropdown-item" href="{{ route('mode_pay_form') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i>Mode Wise Payment</a></li>
                            <li><a class="dropdown-item" href="{{ route('sale_form') }}"><i class="bi bi-file-earmark-bar-graph me-2"></i>Total Sale</a></li>
                        </ul>
                    </li>
                @else
                    <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="bi bi-bag-fill me-2"></i><span>Orders</span>
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
        <div class="topbar d-flex align-items-center">
            <button id="toggleSidebar" class="btn btn-outline-secondary me-3">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0">Welcome, {{ $admin->name }}</h5>
            <h6 class="ms-auto text-right black mb-0" id="datetime"></h6>
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

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ asset('assets/aos/aos.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>
  // Live Clock
  function updateDateTime() {
    const now = new Date();
    const options = {
      weekday: 'short', year: 'numeric', month: 'short', day: 'numeric',
      hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
    };
    document.getElementById('datetime').innerHTML = now.toLocaleString('en-US', options);
  }

  setInterval(updateDateTime, 1000);
  updateDateTime();

  // Loader on form submit
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
      document.getElementById('loader-overlay').style.display = 'flex';
    });
  });

  // Sidebar toggle
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
      });
    }
  });
</script>
</body>
</html>
