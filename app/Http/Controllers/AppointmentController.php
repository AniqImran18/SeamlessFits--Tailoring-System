<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function appointmentIndex(Request $request) {
        $customerID = $request->session()->get('customerID');
        $customer = Customer::find($customerID);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $upcomingAppointments = Appointment::where('customerID', $customerID)
            ->where(function($query) {
                $query->where('date', '>', now()->toDateString())
                      ->orWhere(function($subQuery) {
                          $subQuery->where('date', now()->toDateString())
                                   ->where('time', now()->format('H:i'));
                      });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('customer.appointment-index', compact('customer','upcomingAppointments'));
    }

    public function appointmentCreate(Request $request)
    {
        $customerID = $request->session()->get('customerID');
        $customer = Customer::find($customerID);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $services = Service::all();
        $bookedSlots = Appointment::where('date', '>=', now()->toDateString())
            ->select('date', 'time')
            ->get();

        return view('customer.appointment-create', compact('services', 'customer', 'bookedSlots'));
    }

    public function appointmentStore(Request $request)
    {
        $customerID = $request->session()->get('customerID');

        if (!$customerID) {
            return redirect()->route('customer.login')->with('error', 'Please log in to book an appointment.');
        }

        $request->validate([
            'date' => [
            'required',
            'date',
            'after_or_equal:today',
            function ($attribute, $value, $fail) {
                $dayOfWeek = \Carbon\Carbon::parse($value)->dayOfWeek; // 0 = Sunday, 6 = Saturday
                if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                    $fail('Appointments can only be booked from Monday to Friday.');
                }
            },
        ],
            'time' => 'required|date_format:H:i',
            'serviceID' => 'required|exists:services,serviceID',
        ]);

        $appointmentExists = Appointment::where('date', $request->input('date'))
            ->where('time', $request->input('time'))
            ->exists();

        if ($appointmentExists) {
            return redirect()->back()->withErrors(['time' => 'The selected Date and Time is already booked.'])->withInput();
        }

        Appointment::create([
            'customerID' => $customerID,
            'serviceID' => $request->input('serviceID'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
        ]);

        return redirect()->route('customer.appointment-index')
            ->with('success', 'Appointment created successfully.');
    }

    // Controller Method to Update Appointment

    public function updateAppointmentTailor(Request $request, $appointmentID)
    {
        $validatedData = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
        ]);

        $dayOfWeek = Carbon::parse($validatedData['date'])->dayOfWeek;
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            return response()->json(['error' => 'Appointments can only be made from Monday to Friday.'], 422);
        }

        $appointmentTime = Carbon::createFromFormat('H:i', $validatedData['time'], config('app.timezone')); // Ensure correct timezone
        $startTime = Carbon::createFromTime(8, 0, 0, config('app.timezone')); // Start time
        $endTime = Carbon::createFromTime(18, 0, 0, config('app.timezone')); // End time

        if ($appointmentTime->lt($startTime) || $appointmentTime->gt($endTime)) {
            return response()->json(['error' => 'Appointments can only be made between 08:00 AM and 05:00 PM.'], 422);
        }

        $appointment = Appointment::find($appointmentID);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        $appointmentExists = Appointment::where('date', $validatedData['date'])
            ->where('time', $validatedData['time'])
            ->where('appointmentID', '!=', $appointmentID)
            ->exists();

        if ($appointmentExists) {
            return response()->json(['error' => 'The selected date and time are already booked.'], 422);
        }

        $appointment->date = $validatedData['date'];
        $appointment->time = $validatedData['time'];
        $appointment->save();

        return response()->json(['success' => 'Appointment updated successfully.']);
    }


    public function createAppointmentTailor(Request $request, $appointmentID)
    {
        // Validate the request
        $request->validate([
            'customerID' => 'required|exists:customers,customerID', // Ensure customer exists
            'serviceID' => 'required|exists:services,serviceID',   // Ensure service exists
            'date' => 'required|date|after_or_equal:today', // Date should be today or later
            'time' => 'required|date_format:H:i',          // Time format (HH:MM)
        ]);

        // Check if the date is Monday to Friday
        $dayOfWeek = Carbon::parse($request->input('date'))->dayOfWeek; // 0 = Sunday, 6 = Saturday
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            //return response()->json(['error' => 'Appointments can only be made from Monday to Friday.'], 400);
            return redirect()->back()->with('success','Appointments can only be made from Monday to Friday.');
        }

        // Find the appointment
        $appointment = Appointment::find($appointmentID);

        // Check if the time is within 08:00 and 17:00
        $appointmentTime = Carbon::createFromFormat('H:i', $request->input('time'));
        $startTime = Carbon::createFromTime(8, 0); // 08:00 AM
        $endTime = Carbon::createFromTime(17, 0); // 05:00 PM
        if ($appointmentTime->lt($startTime) || $appointmentTime->gt($endTime)) {
            //return response()->json(['error' => 'Appointments can only be made between 08:00 AM and 05:00 PM.'], 400);
            return redirect()->back()->with('success','Appointments can only be made between 08:00 AM and 05:00 PM.');
        }

        // Check for conflicting appointments
        $appointmentExists = Appointment::where('date', $request->input('date'))
            ->where('time', $request->input('time'))
            ->exists();

        if ($appointmentExists) {
            //return response()->json(['error' => 'The selected date and time are already booked.'], 400);
            return redirect()->back()->with('success','The selected date and time are already booked.');
        }

        // Create the appointment
        $appointment = Appointment::create([
            'customerID' => $request->input('customerID'),
            'serviceID' => $request->input('serviceID'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
        ]);

        //return response()->json(['success' => 'Appointment created successfully!', 'appointment' => $appointment], 200);
        return redirect()->back()->with('success','Appointment created successfully!');
    }

    public function appointmentStoreTailor(Request $request)
    {
        $request->validate([
            'customerID' => 'required|exists:customers,customerID',
            'serviceID' => 'required|exists:services,serviceID',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
        ]);

        // Check for conflicts
        $conflict = Appointment::where('date', $request->date)
            ->where('time', $request->time)
            ->exists();

        if ($conflict) {
            // return response()->json(['error' => 'This time slot is already booked.'], 400);
            return redirect()->back()->with('success','This time slot is already booked.');
        }

        $appointment = Appointment::create([
            'customerID' => $request->customerID,
            'serviceID' => $request->serviceID,
            'date' => $request->date,
            'time' => $request->time,
        ]);

        // return response()->json(['success' => 'Appointment created successfully!', 'appointment' => $appointment]);
        return redirect()->back()->with('success','Appointment created successfully!');
    }


    public function appointmentEdit(Request $request, $appointmentID)
    {
        $customerID = $request->session()->get('customerID');
        $appointment = Appointment::findOrFail($appointmentID);
        $customer = Customer::find($customerID);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $services = Service::all();

        // Fetch all booked slots for the customer
        $bookedSlots = Appointment::where('customerID', $customerID)
            ->where('appointmentID', '!=', $appointment->appointmentID) // Exclude the current appointment
            ->select('date', 'time')
            ->get();

        // Get booked dates for disabling
        $bookedDates = $bookedSlots->pluck('date')->unique()->values()->toArray();

        return view('customer.appointment-edit', compact('appointment', 'services', 'customer', 'bookedSlots', 'bookedDates'));
    }

    public function appointmentUpdate(Request $request, $appointmentID)
    {
        $customerID = $request->session()->get('customerID');
        $appointment = Appointment::findOrFail($appointmentID);
        $customer = Customer::find($customerID);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $request->validate([
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $dayOfWeek = \Carbon\Carbon::parse($value)->dayOfWeek;
                    if (in_array($dayOfWeek, [0, 6])) { // 0 = Sunday, 6 = Saturday
                        $fail(__('Appointments can only be booked on weekdays (Monday to Friday).'));
                    }
                },
            ],
            'time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if the selected time is already booked for the given date
                    $isBooked = \App\Models\Appointment::where('date', $request->input('date'))
                        ->where('time', $value)
                        ->exists();
        
                    if ($isBooked) {
                        $fail(__('The selected time is already booked. Please choose another time.'));
                    }
                },
            ],
            'serviceID' => 'required|exists:services,serviceID',
        ]);
        

        $appointmentExists = Appointment::where('date', $request->input('date'))
            ->where('time', $request->input('time'))
            ->where('appointmentID', '!=', $appointment->appointmentID)
            ->exists();

        if ($appointmentExists) {
            return redirect()->back()->withErrors(['time' => 'The selected Date and Time is already booked.'])->withInput();
        }

        $appointment->update([
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'serviceID' => $request->input('serviceID'),
        ]);

        return redirect()->route('customer.appointment-index', ['customerID' => $appointment->customerID])
            ->with('success', 'Appointment updated successfully.');
    }

    public function appointmentDelete(Request $request, $appointmentID)
    {
        $customerID = $request->session()->get('customerID');
        $appointment = Appointment::findOrFail($appointmentID);
        $customer = Customer::find($customerID);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $appointment->delete();

        return redirect()->route('customer.appointment-index',compact('customer'))
            ->with('success', 'Appointment deleted successfully.');
    }


    
}
