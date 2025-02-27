<?php

namespace App\Http\Controllers;

// MeasurementController.php

use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Http\Request;
use App\Models\Tailor;

class MeasurementController extends Controller
{
    // Method to show the measurement form with customer search
    public function createMeasurement(Request $request)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        $customers = Customer::all();
        return view('tailor.measurement-create',compact('tailor'), ['customers' => $customers]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Fetch customers with their measurements, filtering by search if provided
        $customers = Customer::with('measurements')
            ->where('name', 'LIKE', "%{$search}%")
            ->orWhere('customerID', 'LIKE', "%{$search}%")
            ->get();
        
        return view('tailor.measurement-list', ['customers' => $customers, 'search' => $search]);
    }



    // Method to save measurement data
    public function storeMeasurement(Request $request)
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
    

        if (!$tailorID) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        // Validate the form data
        $request->validate([
            'customerID' => 'required|exists:customers,customerID',
            'length' => 'required|numeric',
            'waist' => 'required|numeric',
            'shoulder' => 'required|numeric',
            'hip' => 'required|numeric',
            'wrist' => 'required|numeric',
            'remark' => 'nullable|string|max:1000', 
        ]);

        // Create a new measurement
        Measurement::create([
            'customerID' => $request->input('customerID'),
            'length' => $request->input('length'),
            'waist' => $request->input('waist'),
            'shoulder' => $request->input('shoulder'),
            'hip' => $request->input('hip'),
            'wrist' => $request->input('wrist'),
            'remark' => $request->input('remark'),
        ]);

        return redirect()->route('tailor.measurement-list')->with('success', 'Measurement created successfully!');
    }

    

    
}

