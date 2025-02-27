<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'default title')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('resources/css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1050; /* Ensures the sidebar appears over the content */
        }
        .sidebar.collapsed {
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
            margin-left: 250px;
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
        <span class="navbar-brand">Customer Dashboard</span>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            @if($customer->profile_picture)
                <img src="{{ asset('storage/' . $customer->profile_picture) }}" alt="Profile Picture">
            @else
                <img src="{{ asset('default-profile.png') }}" alt="Profile Picture">
            @endif
            <h2>Welcome, {{ $customer->name }}</h2>
            
        </div>
        <ul>
            <li><a class="nav-link {{ request()->is('customer/dashboard*') ? 'active' : '' }}" href="{{ route('customer.dashboard', ['customerID' => $customer->customerID]) }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a class="nav-link {{ request()->is('customer/profile-edit*') ? 'active' : '' }}" href="{{ route('customer.profile-edit', ['customerID' => $customer->customerID]) }}"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
            <li>
                <a class="nav-link {{ request()->is('customer/order-history*') ? 'active' : '' }}" 
                   href="{{ route('customer.order-history', ['customerID' => $customer->customerID]) }}" 
                   onclick="orderReminder(event)">
                    <i class="fas fa-clipboard-list"></i> My Orders
                </a>
            </li>            
            <li><a class="nav-link {{ request()->is('customer/service-index*') ? 'active' : '' }}" href="{{ route('customer.service-index', ['customerID' => $customer->customerID]) }}"><i class="fas fa-concierge-bell"></i> Our Service</a></li>
            <li><a class="nav-link {{ request()->is('customer/measurement-index*') ? 'active' : '' }}" href="{{ route('customer.measurement-index', ['customerID' => $customer->customerID]) }}"><i class="fas fa-ruler-combined"></i> My Measurement</a></li>
            <li>
                <a class="nav-link {{ request()->is('customer/appointment-index*') ? 'active' : '' }}" 
                   href="{{ route('customer.appointment-index', ['customerID' => $customer->customerID]) }}" 
                   onclick="appointmentReminder(event)">
                    <i class="fas fa-calendar-alt"></i> Book Appointment
                </a>
            </li>            
            <li>
                <form action="{{ route('customer.logout') }}" method="POST">
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

    <!-- Modal for Order Reminder -->
    <div class="modal fade" id="orderReminderModal" tabindex="-1" aria-labelledby="orderReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderReminderModalLabel">Soft Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Dear customer, soft reminder before placing an order:</p>
                    <ul>
                        <li>For new customers, please make an appointment to add a new measurement.</li>
                        <li>For loyal customers, if you want to add a new measurement, please make a new appointment as well.</li>
                    </ul>
                    <p>Thank you!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="orderLink" class="btn btn-primary">Proceed</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Appointment Reminder -->
    <div class="modal fade" id="appointmentReminderModal" tabindex="-1" aria-labelledby="appointmentReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentReminderModalLabel">Soft Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Dear customer, soft reminder before booking an appointment:</p>
                    <ul>
                        <li>If you want to book for one person, you can book 1 slot for the appointment.</li>
                        <li>If you want to book for your family members or more than 2 people, please book 2 slots.</li>
                    </ul>
                    <p>We appreciate your help. Thank you!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="appointmentLink" class="btn btn-primary">Proceed</a>
                </div>
            </div>
        </div>
    </div>

    

        <!-- JavaScript for Sidebar Toggle -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Order Reminder Modal
        function orderReminder(event) {
            event.preventDefault(); // Prevent immediate navigation
            const orderLink = document.getElementById('orderLink');
            orderLink.href = event.target.href; // Set link for redirect
            $('#orderReminderModal').modal('show'); // Show modal
        }

        // Appointment Reminder Modal
        function appointmentReminder(event) {
            event.preventDefault(); // Prevent immediate navigation
            const appointmentLink = document.getElementById('appointmentLink');
            appointmentLink.href = event.target.href; // Set link for redirect
            $('#appointmentReminderModal').modal('show'); // Show modal
        }
    </script>
</body>
</html>
