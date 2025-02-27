<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'default title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @vite('resources/css/dashboard.css') 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
        }
    
        /* Sidebar Styling */
        .sidebar {
            background-color: #212529;
            color: #fff;
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1050; /* Ensures the sidebar appears over the content */
        }
        .sidebar.show {
            transform: translateX(0);
        }
        .sidebar .sidebar-header {
            padding: 20px;
            background-color: #343a40;
            text-align: center;
        }
        .sidebar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #fff;
        }
        .sidebar h2 {
            font-size: 18px;
            color: #fff;
            margin-top: 10px;
        }
        .sidebar p {
            font-size: 13px;
            color: #adb5bd;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #adb5bd;
            font-size: 15px;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #495057;
            color: #f8f9fa;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
    
        /* Content Styling */
        .content {
            margin-left: 0;
            padding: 30px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
    
        /* Button Styles */
        .btn-custom {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #6a67ce;
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #5752b1;
        }
    
        /* Mobile and Responsive Styling */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
            }
            .navbar-toggler {
                display: inline-block;
            }
        }
    
        @media (min-width: 993px) {
            .sidebar {
                transform: translateX(0);
            }
            .content {
                margin-left: 250px;
            }
            .navbar-toggler {
                display: none;
            }
        }
    </style>
    
</head>
<body>

    <!-- Navbar for toggling sidebar on mobile -->
    <nav class="navbar navbar-dark bg-dark d-lg-none">
        <button class="navbar-toggler" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand">Tailor Dashboard</span>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            @if($tailor->profile_picture)
                <img src="{{ asset('storage/' . $tailor->profile_picture) }}" alt="Profile Picture">
            @else
                <img src="{{ asset('default-profile.png') }}" alt="Profile Picture">
            @endif
            <h2>Welcome, {{ $tailor->name }}</h2>
        </div>
        <ul>
            <li><a class="nav-link {{ request()->is('tailor/dashboard*') ? 'active' : '' }}" href="{{ route('tailor.dashboard', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a class="nav-link {{ request()->is('tailor/profile-edit*') ? 'active' : '' }}" href="{{ route('tailor.profile-edit', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
            <li><a class="nav-link {{ request()->is('tailor/order-pending*') ? 'active' : '' }}" href="{{ route('tailor.order-pending', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-clipboard-list"></i> Manage Orders</a></li>
            <li><a class="nav-link {{ request()->is('tailor/service-index*') ? 'active' : '' }}" href="{{ route('tailor.service-index', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-concierge-bell"></i> Manage Service</a></li>
            <li><a class="nav-link {{ request()->is('tailor/customer-list*') ? 'active' : '' }}" href="{{ route('tailor.customer-list', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-users"></i> Manage Customer</a></li>
            <li><a class="nav-link {{ request()->is('tailor/measurement-list*') ? 'active' : '' }}" href="{{ route('tailor.measurement-list', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-ruler-combined"></i> Manage Measurement</a></li>
            <li><a class="nav-link {{ request()->is('tailor/appointment-calendar*') ? 'active' : '' }}" href="{{ route('tailor.appointment-calendar', ['tailorID' => $tailor->tailorID]) }}"><i class="fas fa-calendar-alt"></i> Appointment Calendar</a></li>
            <li>
                <form action="{{ route('tailor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        @yield('content')
        @vite('resources/js/app.js')
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
</body>
</html>
