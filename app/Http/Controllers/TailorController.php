<?php

namespace App\Http\Controllers;

use App\Models\Tailor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderAccepted;
use App\Mail\OrderCompleted;

class TailorController extends Controller
{
    public function showLoginForm()
    {
        return view('tailor.login'); // Replace with your actual view
    }

    public function customerList(Request $request)
    {
        // Search function implementation



        $search = $request->input('search');
        $customers = Customer::where('name', 'LIKE', "%$search%")
            ->orWhere('email', 'LIKE', "%$search%")
            ->orWhere('phone_number', 'LIKE', "%$search%")
            ->get();
        $tailor = Tailor::find($request->session()->get('tailorID'));

        return view('tailor.customer-list', compact('tailor'), ['customers' => $customers]);
    }

    // Method to view customer details
    public function viewCustomer(Request $request,$customerID)
    {
        $customer = Customer::withCount('orders')->findOrFail($customerID);
        $tailor = Tailor::find($request->session()->get('tailorID'));
        return view('tailor.customer-details', compact('tailor','customer'), ['customer' => $customer]);
    }
    public function destroyCustomer(Request $request,$customerID)
    {
        // Find the customer
        $customer = Customer::findOrFail($customerID);
        $tailor = Tailor::find($request->session()->get('tailorID'));

        // Delete the customer
        $customer->delete();

        // Redirect back to the customer list with success message
        return redirect()->route('tailor.customer-list')
                         ->with('success', 'Customer account deleted successfully.');
    }

    public function calendar(Request $request)
    {
        $tailor = Tailor::find($request->session()->get('tailorID'));

        // Retrieve all appointments
        $appointments = Appointment::with(['customer', 'service'])->get();
        $customers = Customer::all(); // Retrieve all customers
        $services = Service::all();   // Retrieve all services

        // Define colors for each service category
        $serviceColors = [
            'Dress Maker' => '#ff4d4d',
            'Alteration' => '#4CAF50',
            'Repair and Restoration' => '#03A9F4',
        ];

        // Format events for FullCalendar
        $events = $appointments->map(function ($appointment) use ($serviceColors) {
            $color = $serviceColors[$appointment->service->category] ?? '#000000'; // Default color
            return [
                'title' => $appointment->service->category . ' - ' . $appointment->customer->name,
                'start' => Carbon::parse($appointment->date . ' ' . $appointment->time)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'appointmentID' => $appointment->appointmentID,
                    'customerName' => $appointment->customer->name,
                    'serviceName' => $appointment->service->category,
                    'date' => $appointment->date,
                    'time' => $appointment->time,
                ],
            ];
        });

        return view('tailor.appointment-calendar', compact('events', 'tailor', 'customers', 'services'));
    }

    public function getAppointmentsByDate(Request $request)
    {
        $tailor = Tailor::find($request->session()->get('tailorID'));

        // Ensure tailor data is available
        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You are not logged in.');
        }
        
        $date = $request->input('date');
        $appointments = Appointment::with(['customer', 'service'])
            ->where('date', $date)
            ->get();

        return response()->json($appointments);
    }

    public function deleteAppointment($appointmentID)
    {
        
        $appointment = Appointment::find($appointmentID);

        if (!$appointment) {
            // Return JSON if appointment not found
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        $appointment->delete();

        // Return success response as JSON
        return response()->json(['success' => 'Appointment deleted successfully.'], 200);
    }


    public function measurementList(Request $request)
    {
        // Check if the user is logged in
        if (!$request->session()->has('tailorID')) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }

        $search = $request->input('search');
        
        // Fetch customers and their measurements, filtered by search
        $customers = Customer::with('measurements')
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('customerID', 'LIKE', "%{$search}%")
            ->get();
        
        $tailor = Tailor::find($request->session()->get('tailorID'));

        // Ensure tailor data is available
        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You are not logged in.');
        }
        
        return view('tailor.measurement-list', compact('tailor'), ['customers' => $customers, 'search' => $search]);
    }

    public function serviceList(Request $request) {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');
        
        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);
    
        if (!$tailor) {
            // If tailor is not found, redirect to the login page or show an error
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
    
        $search = $request->input('search');
        
        if ($search) {
            // Search by name or category
            $services = Service::where('name', 'LIKE', "%{$search}%")
                        ->orWhere('category', 'LIKE', "%{$search}%")
                        ->get()
                        ->groupBy('category');
        } else {
            // No search query, show all services grouped by category
            $services = Service::all()->groupBy('category');
        }
    
        // Pass the tailor info and services data to the view
        return view('tailor.service-index', compact('tailor', 'services', 'search'));
    }

    public function pendingOrders(Request $request)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');
        
        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            // If tailor is not found, redirect to the login page or show an error
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        // Fetch orders with the status 'pending' or 'in_process'
        $pendingOrders = Order::whereIn('status', ['pending', 'in_process'])->with(['customer', 'services'])->get();

        $completeOrder = Order::where('status', 'completed')->with(['customer', 'services'])->get();

        return view('tailor.order-pending', compact('pendingOrders', 'tailor' , 'completeOrder'));
    }



    public function acceptOrder(Request $request, $orderID)
    {
        $tailorID = $request->session()->get('tailorID');

        if (!$tailorID) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }

        $order = Order::findOrFail($orderID);
        $order->status = 'in_process';
        $order->tailorID = $tailorID; // Correctly assign the tailor ID
        $order->save();

        $activity = "Order #{$orderID} was accepted";
        $recentActivities = session()->get('recentActivities', []);
        $recentActivities[] = $activity;
        session()->put('recentActivities', $recentActivities);

        // Send email notification to the customer
        Mail::to($order->customer->email)->send(new OrderAccepted($order));

        return redirect()->route('tailor.order-pending')->with('message', 'Order accepted and notification sent to the customer.');
    }


    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);

        // Retrieve the tailor using the phone number
        $tailor = Tailor::where('phone_number', $request->phone_number)->first();

        // Check if the tailor exists and if the password matches
        if ($tailor && Hash::check($request->password, $tailor->password)) {
            // Store the tailor ID in the session
            $request->session()->put('tailorID', $tailor->tailorID);
            return redirect()->route('tailor.dashboard')->with('success', 'Login successful.');
        }

        // If login fails, redirect back with an error
        return back()->withErrors([
            'phone_number' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showRegistrationForm()
    {
        return view('tailor.register'); // Replace with your actual view
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image upload
        ]);

        // Create the tailor
        $tailor = new Tailor();
        $tailor->name = $request->name;
        $tailor->password = Hash::make($request->password);
        $tailor->phone_number = $request->phone_number;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $filename, 'public'); // Store in storage/app/public/profile_pictures
            $tailor->profile_picture = 'profile_pictures/' . $filename; // Save the path
        }

        $tailor->save();

        return redirect()->route('tailor.login')->with('success', 'Registration successful. Please log in.');
    }

    // dashboard
    public function dashboard(Request $request)
    {
        // Check if the user is logged in
        if (!$request->session()->has('tailorID')) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }

        // Retrieve the tailor's information from the database
        $tailor = Tailor::find($request->session()->get('tailorID'));

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'Tailor not found.');
        }

        // Count the total number of orders in process
        $inProcessOrders = Order::where('status', 'in_process')->count();

        // Count the total number of orders pending acceptance
        $pendingOrders = Order::where('status', 'pending')->count();

        // Count the total number of appointments (filtering out past ones)
        $appointments = Appointment::whereDate('date', '>=', today())->count();

        // Count the total number of customers
        $totalCustomers = Customer::count();

        $newOrders = Order::where('status', 'Pending')->latest()->limit(5)->get();
        $newOrdersCount = $newOrders->count();


        // Fetch recent activities
        $recentActivities = session()->get('recentActivities', []);

        return view('tailor.dashboard', compact('tailor', 'inProcessOrders', 'pendingOrders', 'appointments', 'totalCustomers', 'newOrders', 'newOrdersCount', 'recentActivities'));
    }


    public function logout(Request $request)
    {
        // Clear the session for the tailor
        $request->session()->forget('tailorID');
        return view('welcome')->with('success', 'Logged out successfully.');
    }

    // Show the profile edit form
    public function edit($tailorID) // Accept the tailorID as a parameter
    {
        // Find the tailor by ID
        $tailor = Tailor::find($tailorID);

        // Check if the tailor exists
        if (!$tailor) {
            return redirect()->route('tailor.dashboard')->with('error', 'Tailor not found.');
        }

        // Return the view with the tailor's information
        return view('tailor.profile-edit', compact('tailor'));
    }

    // Handle the profile update
    public function update(Request $request, $tailorID)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional profile picture validation
        ]);

        // Find the tailor by ID
        $tailor = Tailor::find($tailorID);

        // Check if the tailor exists
        if (!$tailor) {
            return redirect()->route('tailor.dashboard')->with('error', 'Tailor not found.');
        }

        // Update tailor's information
        $tailor->name = $request->name;
        $tailor->phone_number = $request->phone_number;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($tailor->profile_picture) {
                Storage::delete($tailor->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $tailor->profile_picture = $path;
        }

        // Save the updated tailor information
        $tailor->save();

        return redirect()->route('tailor.dashboard')->with('success', 'Profile updated successfully.');
    }

    public function completeOrder(Request $request, $orderId)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }

        // Find the order by ID
        $order = Order::find($orderId);

        if (!$order || $order->status !== 'in_process') {
            return redirect()->back()->with('error', 'Order not found or not in process.');
        }

        // Update the order status to completed
        $order->status = 'completed';
        $order->save();

        // Send an email notification to the customer
        Mail::to($order->customer->email)->send(new OrderCompleted($order));

        return redirect()->back()->with('success', 'Order marked as complete and notification sent.');
    }

    public function viewOrderDetails(Request $request, $orderID)
    {
        $tailorID = $request->session()->get('tailorID');
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }

        $order = Order::with(['customer', 'orderServices.service', 'orderServices.measurement'])->findOrFail($orderID);

        return view('tailor.order-details', compact('order', 'tailor'));
    }

    public function editMeasurement(Request $request,$measurementID)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        $measurement = Measurement::findOrFail($measurementID);
        return view('tailor.measurement-edit',compact('tailor'), ['measurement' => $measurement]);
    }

    // Method to update measurement data
    public function updateMeasurement(Request $request, $measurementID)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        $validatedData = $request->validate([
            'length' => 'required|numeric',
            'waist' => 'required|numeric',
            'shoulder' => 'required|numeric',
            'hip' => 'required|numeric',
            'wrist' => 'required|numeric',
            'remark' => 'nullable|string|max:1000',
        ]);

        $measurement = Measurement::findOrFail($measurementID);
        $measurement->update($validatedData);

        return redirect()->route('tailor.measurement-details', ['customerID' => $measurement->customerID])
                        ->with('success', 'Measurement updated successfully!');
    }


    // Method to delete a measurement
    public function deleteMeasurement(Request $request,$measurementID)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        $measurement = Measurement::findOrFail($measurementID);
        $measurement->delete();

        return redirect()->route('tailor.measurement-list',compact('tailor'))->with('success', 'Measurement deleted successfully!');
    }

    public function viewMeasurementDetails(Request $request,$customerID)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        $customer = Customer::with('measurements')->findOrFail($customerID);
        return view('tailor.measurement-details', compact('tailor'), ['customer' => $customer]);
    }

    
}
