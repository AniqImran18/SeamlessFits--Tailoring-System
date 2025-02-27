@extends('layout.tailor')

@section('title', 'Appointment Calendar')

@section('content')



<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">Appointment Calendar</h1>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<style>
    .fc-event {
        color: black;
        border-radius: 8px;
        padding: 8px;
    }

    .fc-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fc .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<!-- FullCalendar CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

<!-- Bootstrap JS Bundle (for Bootstrap tooltips, modals, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@if(session('success'))
<script>alert( "{{ session('success') }}" );</script>
@endif

<!-- Modal for Appointments on a Selected Date -->
<div class="modal fade" id="appointmentsModal" tabindex="-1" aria-labelledby="appointmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentsModalLabel">Appointments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button class="btn btn-primary mb-3" id="createAppointmentButton">Create New Appointment</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Viewing/Updating Appointment Details -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateAppointmentForm" method="POST" onsubmit="event.preventDefault(); submitUpdateForm();">
                    @csrf
                    @method('PUT')
                    <!-- Hidden input to pass appointment ID -->
                    <input type="hidden" name="appointmentID" id="appointmentID">

                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer</label>
                        <input type="text" class="form-control" id="customerName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="serviceName" class="form-label">Service</label>
                        <input type="text" class="form-control" id="serviceName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" id="date" onchange="validateDate()" required>
                    </div>
                    <div class="mb-3">
                        <label for="time" class="form-label">Time</label>
                        <input type="time" class="form-control" name="time" id="time" min="08:00" max="17:00" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <button class="btn btn-danger mt-3" onclick="deleteAppointment()">Delete Appointment</button>
            </div>
        </div>
    </div>
</div>


    <!-- Modal for Creating Appointment -->
    <div class="modal fade" id="createAppointmentModal" tabindex="-1" aria-labelledby="createAppointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAppointmentModalLabel">Create Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createAppointmentForm" method="POST" action="{{ route('tailor.appointments.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="customerID" class="form-label">Customer</label>
                            <select class="form-select" name="customerID" id="customerID" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->customerID }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="serviceID" class="form-label">Service</label>
                            <select class="form-select" name="serviceID" id="serviceID" required>
                                <option value="">Select Service</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->serviceID }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="date" id="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <select class="form-select" name="time" id="time" required>
                                <option value="">Select Time</option>
                                @for ($hour = 8; $hour < 17; $hour++) <!-- Generate time slots from 08:00 AM to 05:00 PM -->
                                    <option value="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d:00', $hour) }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



<script>
    // Function to submit the Update form
    function submitUpdateForm() {
    const appointmentID = document.getElementById('appointmentID').value; // Retrieve appointment ID from modal

    if (!appointmentID) {
        console.error("Error: appointmentID is missing.");
        alert("Error: Could not update the appointment. Appointment ID is missing.");
        return;
    }

    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;

    // Validate the inputs before sending the request
    if (!date || !time) {
        alert("Please fill in both the date and time.");
        return;
    }

    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;

    if (!csrfToken) {
        console.error("Error: CSRF token is missing.");
        alert("Error: Could not update the appointment. CSRF token is missing.");
        return;
    }

    fetch(`/tailor/appointments/${appointmentID}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken, // Use the CSRF token
        },
        body: JSON.stringify({
            date: date,
            time: time,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                // Check for validation errors or server errors
                return response.json().then((data) => {
                    throw new Error(data.error || "Failed to update the appointment.");
                });
            }
            return response.json(); // Parse the successful JSON response
        })
        .then((data) => {
            if (data.success) {
                alert(data.success); // Show success message
                location.reload(); // Reload the page or calendar to reflect changes
            } else if (data.error) {
                alert(data.error); // Show error message from the server
            }
        })
        .catch((error) => {
            console.error('Error:', error.message);
            alert(`Failed to update the appointment. ${error.message}`);
        });
}



        document.addEventListener('DOMContentLoaded', function () {
            const dateInputs = document.querySelectorAll('input[type="date"]');

            // Add event listeners for all date inputs
            dateInputs.forEach((dateInput) => {
                dateInput.addEventListener('input', function () {
                    const selectedDate = new Date(this.value);
                    const dayOfWeek = selectedDate.getDay(); // Get the day of the week

                    // Block weekends: 0 = Sunday, 6 = Saturday
                    if (dayOfWeek === 0 || dayOfWeek === 6) {
                        alert('Appointments can only be made from Monday to Friday.');
                        this.value = ''; // Clear the invalid date
                    }
                });
            });

            const timeInputs = document.querySelectorAll('input[type="time"]');

            // Validate working hours for time inputs
            timeInputs.forEach((timeInput) => {
                timeInput.addEventListener('input', function () {
                    const selectedTime = this.value;

                    if (selectedTime < '08:00' || selectedTime > '17:00') {
                        alert('Appointments can only be made between 08:00 AM and 05:00 PM.');
                        this.value = ''; // Clear invalid time
                    }
                });
            });
        });




    // Function to delete the appointment
    function deleteAppointment() {
        const appointmentID = document.getElementById('appointmentID').value;

        if (confirm('Are you sure you want to delete this appointment?')) {
            fetch(`/tailor/appointments/${appointmentID}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        // Handle non-JSON responses
                        throw new Error('Failed to delete the appointment.');
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        alert(data.success);
                        location.reload(); // Reload the calendar
                    } else {
                        alert(data.error);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    }

    // Handle Create Appointment Form Submission
    function createAppointmentForm() {
        document.getElementById('createAppointmentForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const customerID = document.getElementById('customerID').value;
            const serviceID = document.getElementById('serviceID').value;
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;

            // fetch(`/tailor/appointments/store-appointment`, {
            fetch(`{{ route('tailor.appointments.store')}}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    customerID: customerID,
                    serviceID: serviceID,
                    date: date,
                    time: time,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to create the appointment.');
            });
        });
    }

        // Function to view appointment details
            // window.viewAppointmentDetails = function (details) {
            //     document.getElementById('customerName').value = details.customerName;
            //     document.getElementById('serviceName').value = details.serviceName;
            //     document.getElementById('date').value = details.date;
            //     document.getElementById('time').value = details.time;

            //     // Set the hidden appointment ID field
            //     document.getElementById('appointmentID').value = details.appointmentID;

            //     // Show the modal
            //     var modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
            //     modal.show();
            // };

                // Open Create Appointment Modal when the button is clicked
                document.getElementById('createAppointmentButton').addEventListener('click', function () {
                var modal = new bootstrap.Modal(document.getElementById('createAppointmentModal'));
                modal.show();
                });
    
    


    // Main Calendar JavaScript
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek',
        },
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5], // Monday to Friday
            startTime: '08:00', // 8 AM
            endTime: '17:00',   // 5 PM
        },
        dateClick: function (info) {
            const selectedDate = new Date(info.dateStr);
            const dayOfWeek = selectedDate.getDay();

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                alert("Appointments can only be made from Monday to Friday.");
                return;
            }

            fetchAppointmentsByDate(info.dateStr);
        },
        eventClick: function (info) {
            viewAppointmentDetails(info.event.extendedProps);
        },
        events: @json($events),
    });
        calendar.render();

        function fetchAppointmentsByDate(date) {
            fetch(`/tailor/appointments?date=${date}`)
                .then(response => response.json())
                .then(appointments => {
                    var tbody = document.getElementById('appointmentList');
                    tbody.innerHTML = '';
                    appointments.forEach(appointment => {
                        var tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${appointment.customer.name}</td>
                            <td>${appointment.service.category}</td>
                            <td>${appointment.date}</td>
                            <td>${appointment.time}</td>
                        `;
                        tbody.appendChild(tr);
                    });

                    var modal = new bootstrap.Modal(document.getElementById('appointmentsModal'));
                    modal.show();
                });
        }

        // Function to view appointment details
        window.viewAppointmentDetails = function (details) {
            document.getElementById('customerName').value = details.customerName;
            document.getElementById('serviceName').value = details.serviceName;
            document.getElementById('date').value = details.date;
            document.getElementById('time').value = details.time;

            // Set the hidden appointment ID field
            document.getElementById('appointmentID').value = details.appointmentID;

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
            modal.show();
        };
    });
</script>

@endsection
