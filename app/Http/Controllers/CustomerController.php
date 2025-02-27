<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use App\Models\Measurement;
use App\Models\Appointment;
use App\Models\Order;

class CustomerController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.login'); // Replace with your actual view
    }

    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Retrieve the customer using the email
        $customer = Customer::where('email', $request->email)->first();

        // Check if the customer exists and if the password matches
        if ($customer && Hash::check($request->password, $customer->password)) {
            // Store the customer ID in the session
            $request->session()->put('customerID', $customer->customerID);
            return redirect()->route('customer.dashboard')->with('success', 'Login successful.');
        }

        // If login fails, redirect back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('customer.register'); // Replace with your actual view
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/u|max:255', // Only letters and spaces
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ], // Requires at least one uppercase, one lowercase, one number, and one special character
            'phone_number' => 'required|string|max:12',
            'email' => 'required|email|unique:customers',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the customer
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->password = Hash::make($request->password);
        $customer->phone_number = $request->phone_number;
        $customer->email = $request->email;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $filename, 'public'); // Store in storage/app/public/profile_pictures
            $customer->profile_picture = 'profile_pictures/' . $filename; // Save the path
        }

        $customer->save();

        return redirect()->route('customer.login')->with('success', 'Registration successful. Please log in.');
    }

    public function CustomerserviceList(Request $request) {
        // Get the logged-in customer's ID from the session
        $customerID = $request->session()->get('customerID');
        
        // Fetch the logged-in tailor's information
        $customer = Customer::find($customerID);
    
        if (!$customer) {
            // If customer is not found, redirect to the login page or show an error
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
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
        return view('customer.service-index', compact('customer', 'services', 'search'));
    }

    // dashboard
    public function dashboard(Request $request)
    {
        // Check if the user is logged in
        if (!$request->session()->has('customerID')) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        // Retrieve the customer's information from the database
        $customerID = $request->session()->get('customerID');
        $customer = Customer::find($customerID);

        // Ensure the customer exists
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Customer not found. Please log in again.');
        }

        // Count pending orders
        $pendingOrdersCount = Order::where('customerID', $customerID)
                                    ->where('status', 'pending')
                                    ->count();

        // Count completed orders
        $completedOrdersCount = Order::where('customerID', $customerID)
                                    ->where('status', 'completed')
                                    ->count();

        // Count upcoming appointments
        $upcomingAppointmentsCount = Appointment::where('customerID', $customerID)
                                                ->whereDate('date', '>=', now())
                                                ->count();

        // Pass data to the view
        return view('customer.dashboard', compact(
            'customer',
            'pendingOrdersCount',
            'completedOrdersCount',
            'upcomingAppointmentsCount'
        ));
    }


    public function logout(Request $request)
    {
        // Clear the session for the customer
        $request->session()->forget('customerID');
        return view('welcome')->with('success', 'Logged out successfully.');
    }

    // Show the profile edit form
    public function edit($customerID) // Accept the customerID as a parameter
    {
        // Find the customer by ID
        $customer = Customer::find($customerID);
        
        // Check if the customer exists
        if (!$customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Customer not found.');
        }

        // Return the view with the customer's information
        return view('customer.profile-edit', compact('customer'));
    }

    // Handle the profile update
    public function update(Request $request, $customerID)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/u|max:255', // Only letters and spaces
            'phone_number' => 'required|string|max:12',
            'email' => 'required|email|unique:customers,email,' . $customerID . ',customerID',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image upload
        ]);

        // Find the customer by ID
        $customer = Customer::find($customerID);

        // Check if the customer exists
        if (!$customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Customer not found.');
        }

        // Update customer's information
        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->email = $request->email;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($customer->profile_picture && Storage::exists('public/' . $customer->profile_picture)) {
                Storage::delete('public/' . $customer->profile_picture);
            }

            // Store new profile picture and update path in database
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $customer->profile_picture = $path;
        }

        // Save the updated customer information
        $customer->save();

        return redirect()->route('customer.dashboard')->with('success', 'Profile updated successfully.');
    }

    public function viewMeasurements(Request $request)
    {
        // Get customerID from the query string or session if available
        $customerID = $request->query('customerID') ?? $request->session()->get('customerID');

        if (!$customerID) {
            return redirect()->route('customer.login')->with('error', 'You need to log in to view your measurements.');
        }

        // Retrieve the customer information and measurements for the given customerID
        $customer = Customer::find($customerID);
        $measurements = Measurement::where('customerID', $customerID)->get();

        // Pass both customer and measurements to the view
        return view('customer.measurement-index', compact('customer', 'measurements'));
    }

    
    public function appointmentIndex(Request $request)
    {
        $customerID = $request->session()->get('customerID');

        if (!$customerID) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $customer = Customer::find($customerID);

        $search = $request->input('search');
        $query = Appointment::where('customerID', $customerID)->orderBy('date')->orderBy('time');

        if ($search) {
            $query->whereHas('service', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        $upcomingAppointments = $query->get();

        return view('customer.appointment-index', compact('customer', 'upcomingAppointments'));
    }

    public function viewOrderHistory(Request $request)
    {
        $customerID = $request->session()->get('customerID');

        if (!$customerID) {
            return redirect()->route('customer.login')->with('error', 'You need to log in to view your orders.');
        }

        $customer = Customer::find($customerID);
        $orders = Order::where('customerID', $customerID)->with(['orderServices.service', 'orderServices.measurement'])->get();

        return view('customer.order-history', compact('customer', 'orders'));
    }

    public function viewReceipt(Request $request, $orderID)
    {
        $customerID = $request->session()->get('customerID');

        if (!$customerID) {
            return redirect()->route('customer.login')->with('error', 'You need to log in to view your orders.');
        }

        $customer = Customer::find($customerID);
        
        $order = Order::with(['orderServices.service', 'orderServices.measurement'])
                    ->where('orderID', $orderID)
                    ->where('status', 'completed')
                    ->first();

        if (!$order) {
            return redirect()->route('customer.order-history')->with('error', 'Receipt not available.');
        }

        return view('customer.receipt', compact('order', 'customer'));
    }

}
