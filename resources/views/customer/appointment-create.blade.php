@extends('layout.customer')

@section('title', 'Book Appointment')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-0"></div>
        <div class="col">
            <h1 class="my-4">Book Appointment</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.appointment-store') }}" method="POST" id="appointmentForm">
                @csrf

                <div class="form-group">
                    <label for="date">Select Date</label>
                    <input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}" required>
                </div>

                <div class="form-group">
                    <label for="time">Select Time</label>
                    <select id="time" name="time" class="form-control" required>
                        <option value="">-- Select Time --</option>
                        <!-- Time options will be populated by JavaScript based on the selected date -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="serviceID">Select Service</label>
                    <select id="serviceID" name="serviceID" class="form-control" required>
                        <option value="">-- Select Service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->serviceID }}" {{ old('serviceID') == $service->serviceID ? 'selected' : '' }}>
                                {{ $service->name}}
                            </option>
                        @endforeach
                    </select>
                </div><br><br>

                <button type="submit" class="btn btn-primary">Create Appointment</button>
                <a href="{{ route('customer.appointment-index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookedSlots = @json($bookedSlots);
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');

        // Set minimum date to today
        dateInput.min = new Date().toISOString().split("T")[0];

        dateInput.addEventListener('change', function () {
            const selectedDate = this.value;
            const selectedDay = new Date(selectedDate).getDay(); // Get day of the week (0 = Sunday, 6 = Saturday)

            // Check if the selected day is Saturday (6) or Sunday (0)
            if (selectedDay === 0 || selectedDay === 6) {
                alert("Appointments are only available from Monday to Friday.");
                this.value = ""; // Clear the selected date
                return;
            }
            
            timeInput.innerHTML = '<option value="">-- Select Time --</option>';

            // Filter booked slots for the selected date
            const disabledTimes = bookedSlots
                .filter(slot => slot.date === selectedDate)
                .map(slot => ({
                    start: slot.time,
                    end: new Date(`1970-01-01T${slot.time}`).setMinutes(new Date(`1970-01-01T${slot.time}`).getMinutes() + 60)
                }));

            const startTime = 8; // start time in hours (9 AM)
            const endTime = 17;  // end time in hours (5 PM)
            const interval = 60; // appointment duration in minutes

            // Generate time slots for the entire day in 60-minute intervals
            for (let hour = startTime; hour < endTime; hour++) {
                for (let minutes = 0; minutes < 60; minutes += interval) {
                    let timeOption = `${hour.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    let optionEnd = new Date(`1970-01-01T${timeOption}`).setMinutes(new Date(`1970-01-01T${timeOption}`).getMinutes() + 60);
                    
                    // Check for overlap with booked slots
                    let isDisabled = disabledTimes.some(slot => {
                        const optionStart = new Date(`1970-01-01T${timeOption}`);
                        return (optionStart >= new Date(`1970-01-01T${slot.start}`) && optionStart < slot.end) ||
                               (optionEnd > new Date(`1970-01-01T${slot.start}`) && optionEnd <= slot.end);
                    });

                    // Create and append option
                    let option = document.createElement('option');
                    option.value = timeOption;
                    option.textContent = timeOption;
                    if (isDisabled) {
                        option.disabled = true;
                    }
                    timeInput.appendChild(option);
                }
            }
        });
    });
</script>

@endsection
