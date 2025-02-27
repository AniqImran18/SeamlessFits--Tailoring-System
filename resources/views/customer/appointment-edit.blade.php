@extends('layout.customer')

@section('title', 'Edit Appointment')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">Edit Appointment</h1>
        </div>

        <div class="card-body">
            <div class="card-body">
                <!-- Bootstrap alert for booked time -->
                <div id="timeAlert" class="alert alert-danger d-none" role="alert">
                    The selected time is already booked. Please choose another time.
                </div>


            <form action="{{ route('customer.appointment-update', $appointment->appointmentID) }}" method="POST">

                @csrf
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $appointment->date) }}" required 
                        onchange="updateAvailableTimes()" 
                        min="{{ \Carbon\Carbon::today()->toDateString() }}">
                </div>

                <div class="form-group">
                    <label for="time">Time</label>
                    <select name="time" id="time" class="form-control" required>
                        <option value="">-- Select Time --</option>
                        <!-- Time slots will be populated via JavaScript -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="serviceID">Service</label>
                    <select name="serviceID" id="serviceID" class="form-control" required>
                        @foreach($services as $service)
                            <option value="{{ $service->serviceID }}" {{ $service->serviceID == $appointment->serviceID ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('customer.appointment-index') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookedSlots = @json($bookedSlots); // Fetch booked slots from the controller
        const currentAppointmentTime = '{{ $appointment->time }}'; // Current appointment time
        const dateField = document.getElementById('date');
        const timeField = document.getElementById('time');
        const timeAlert = document.getElementById('timeAlert');

        const updateTimeSlots = (selectedDate) => {
            timeField.innerHTML = '<option value="">-- Select Time --</option>';
            const startTime = 8; // 8 AM
            const endTime = 16; // 4 PM (last booking slot)
            const interval = 1; // 1-hour intervals

            for (let hour = startTime; hour <= endTime; hour++) {
                const timeOption = `${hour.toString().padStart(2, '0')}:00`;

                // Check if the slot is already booked for the selected date
                const isBooked = bookedSlots.some(slot => 
                    slot.date === selectedDate && slot.time === timeOption && timeOption !== currentAppointmentTime
                );

                const option = document.createElement('option');
                option.value = timeOption;
                option.textContent = timeOption;

                if (isBooked) {
                    option.disabled = true;
                    option.textContent += ' (Booked)';
                }

                if (timeOption === currentAppointmentTime) {
                    option.selected = true; // Pre-select the current time
                }

                timeField.appendChild(option);
            }
        };

        // Initialize slots when the page loads
        const initialDate = dateField.value;
        if (initialDate) {
            updateTimeSlots(initialDate);
        }

        // Update time slots when the date changes
        dateField.addEventListener('change', function () {
            const selectedDate = this.value;

            // Disable weekends
            const selectedDay = new Date(selectedDate).getDay();
            if (selectedDay === 0 || selectedDay === 6) {
                alert('Appointments can only be booked on weekdays (Monday to Friday).');
                this.value = '';
                return;
            }

            updateTimeSlots(selectedDate);
        });

        // Check for time conflicts
        timeField.addEventListener('change', function () {
            const selectedDate = dateField.value;
            const selectedTime = timeField.value;

            const isBooked = bookedSlots.some(slot => 
                slot.date === selectedDate && slot.time === selectedTime
            );

            // Show or hide the alert based on booking status
            if (isBooked) {
                timeAlert.classList.remove('d-none');
            } else {
                timeAlert.classList.add('d-none');
            }
        });
    });
</script>

@endsection
