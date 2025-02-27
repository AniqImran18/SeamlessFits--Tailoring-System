<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\Tailor;

class ServiceController extends Controller
{
    // Tailor can manage services
    public function index(Request $request) {
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

        return view('tailor.service-index', compact('services', 'search'));
    }

    public function createService(Request $request) 
    {
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        return view('tailor.service-create',compact('tailor'));
    }

    public function storeService(Request $request) {
        
        // Get the logged-in tailor's ID from the session
        $tailorID = $request->session()->get('tailorID');

        if (!$tailorID) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);
    
        Service::create([
            'category' => $request->input ('category'),
            'name' => $request->input ('name'),
            'price' => $request->input ('price'),
        ]);
    
        return redirect()->route('tailor.service-index')->with('success', 'Service added successfully.');
    }

    public function edit(Request $request,Service $service) {

        $tailorID = $request->session()->get('tailorID');

        // Fetch the logged-in tailor's information
        $tailor = Tailor::find($tailorID);

        if (!$tailor) {
            return redirect()->route('tailor.login')->with('error', 'You need to log in first.');
        }
        return view('tailor.service-edit', compact('service','tailor'));
    }

    public function update(Request $request, Service $service) {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        $service->update($request->all());
        return redirect()->route('tailor.service-index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service) {
        $service->delete();
        return redirect()->route('tailor.service-index')->with('success', 'Service deleted successfully.');
    }

    // Customer can view services
    public function view() {
        $services = Service::all()->groupBy('category');
        return view('customer.view', compact('services'));
    }
}

