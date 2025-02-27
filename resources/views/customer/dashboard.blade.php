@extends('layout.customer')

@section('title', 'Dashboard')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-primary fw-bold">Dashboard</h1>
    <p class="lead text-muted">Welcome to your dashboard. Manage your profile, appointments, and orders with ease.</p>

    <!-- Overview Section -->
    <div class="row mt-4">
        <!-- Total Orders Pending -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center mb-2 rounded-1">
                <div class="card-body py-2">
                    <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                    <h5 class="card-title fw-semibold">Pending Orders</h5>
                    <p class="fs-3 fw-bold text-dark">{{ $pendingOrdersCount }}</p>
                </div>
            </div>
        </div>
        <!-- Total Orders Completed -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center mb-2 rounded-1">
                <div class="card-body py-2">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h5 class="card-title fw-semibold">Completed Orders</h5>
                    <p class="fs-3 fw-bold text-dark">{{ $completedOrdersCount }}</p>
                </div>
            </div>
        </div>
        <!-- Upcoming Appointments -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center mb-2 rounded-1">
                <div class="card-body py-2">
                    <i class="fas fa-calendar-alt fa-2x text-primary mb-3"></i>
                    <h5 class="card-title fw-semibold">Upcoming Appointments</h5>
                    <p class="fs-3 fw-bold text-dark">{{ $upcomingAppointmentsCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div class="mt-5">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-5">
                <h4 class="card-title text-primary fw-bold mb-4">Welcome to Orchid Tailoring</h4>
                <p class="card-text fs-5 text-muted">
                    Dear customer, welcome to <strong>Orchid Tailoring</strong>, your trusted tailoring service provider. 
                </p>
                <p class="card-text text-muted">
                    <strong>How it works:</strong>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item border-0">If you’re new, start by making an appointment to record your measurements.</li>
                        <li class="list-group-item border-0">Measurements include length, waist, shoulder, hip, and wrist.</li>
                        <li class="list-group-item border-0">Once your measurements are recorded, place an order by selecting a service.</li>
                        <li class="list-group-item border-0">Orders are completed within one week, and you’ll be notified once ready.</li>
                    </ol>
                </p>
                <div class="alert alert-info mt-4 rounded-3">
                    <strong>Good News:</strong> For every 5 orders placed, enjoy a <strong>10% discount</strong> on your next order! Thank you for choosing Orchid Tailoring.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
