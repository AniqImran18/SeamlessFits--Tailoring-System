@extends('layout.tailor')

@section('title', 'Dashboard')

@section('content')

<div class="container mt-4">
    <!-- Dashboard Heading -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h1 class="h5 mb-0">Dashboard</h1>
        </div>
        <div class="card-body">
            <p>Welcome to your dashboard, where you can manage your tailoring business efficiently.</p>
        </div>
    </div>

    <!-- Dashboard Metrics Section -->
    <div class="row mt-4">
        <!-- Total Orders in Process -->
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-gear-fill display-5 me-3"></i>
                    <div>
                        <h5 class="card-title">In-Process Orders</h5>
                        <p class="card-text fs-4">{{ $inProcessOrders }}</p> <!-- Dynamic count here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders to Accept -->
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-check-circle-fill display-5 me-3"></i>
                    <div>
                        <h5 class="card-title">Orders to Accept</h5>
                        <p class="card-text fs-4">{{ $pendingOrders }}</p> <!-- Dynamic count here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Appointments -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-calendar-check-fill display-5 me-3"></i>
                    <div>
                        <h5 class="card-title">Total Appointments</h5>
                        <p class="card-text fs-4">{{ $appointments }}</p> <!-- Dynamic count here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-md-3">
            <div class="card text-white bg-secondary shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-people-fill display-5 me-3"></i>
                    <div>
                        <h5 class="card-title">Total Customers</h5>
                        <p class="card-text fs-4">{{ $totalCustomers }}</p> <!-- Dynamic count here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    
</div>

@endsection
