@extends('layout.customer')

@section('title', 'Book Appointment')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h5 mb-0">Upcoming Appointments</h1>
            <a href="{{ route('customer.appointment-create') }}" class="btn btn-sm btn-light text-primary">Create New Appointment</a>
        </div>

        <div class="card-body">
            @if($upcomingAppointments->isEmpty())
                <div class="alert alert-info text-center">No upcoming appointments.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mt-2">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Service</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td class="align-middle">{{ $appointment->date }}</td>
                                    <td class="align-middle">{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</td>
                                    <td class="align-middle">{{ $appointment->service->category }}</td>
                                    <td class="align-middle">
                                        <a href="{{ route('customer.appointment-edit', $appointment->appointmentID) }}" class="btn btn-sm btn-warning">Update</a>
                        
                                        <form action="{{ route('customer.appointment-delete', $appointment->appointmentID) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>                        
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
