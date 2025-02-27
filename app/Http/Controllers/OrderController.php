<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Measurement;
use App\Models\Service;
use App\Models\Customer;
use App\Models\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DiscountNotification;

class OrderController extends Controller
{
    // Display the form for creating a new order
    public function createOrderForm(Request $request)
    {
        $customerID = $request->session()->get('customerID');
        $customer = Customer::find($customerID);

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'You need to log in first.');
        }

        $services = Service::all();
        $measurements = Measurement::where('customerID', $customerID)->get();
        
        return view('customer.order-create', compact('services', 'customer', 'measurements'));
    }

    // Store the new order in the database
    public function Orderstore(Request $request)
    {
        $customerID = $request->session()->get('customerID');

        if (!$customerID) {
            return redirect()->route('customer.login')->with('error', 'Please log in to create an order.');
        }

        // Validate input
        $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,serviceID',
            'measurementRemarks' => 'required|array',
            'measurementRemarks.*' => 'exists:measurements,measurementID',
            'additionalRemarks' => 'nullable|array',
            'additionalRemarks.*' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        // Ensure arrays are of the same size
        if (count($request->services) !== count($request->measurementRemarks)) {
            return redirect()->back()->with('error', 'Each service must have a corresponding measurement.');
        }

        // Create the order
        $order = Order::create([
            'date' => now()->toDateString(),
            'time' => now()->toTimeString(),
            'remark' => $request->remark,
            'status' => 'pending',
            'customerID' => $customerID,
        ]);

        // Add services to the order
        foreach ($request->services as $index => $serviceID) {
            OrderService::create([
                'orderID' => $order->orderID,
                'serviceID' => $serviceID,
                'measurementID' => $request->measurementRemarks[$index],
                'additionalRemark' => $request->additionalRemarks[$index] ?? null,
            ]);
        }

        // Check if the customer is eligible for a discount
        $orderCount = Order::where('customerID', $customerID)->count();
        if ($orderCount % 5 === 0) { // Apply discount only on 5th, 10th, 15th, etc., orders
            $customer = Customer::find($customerID);
            if ($customer && $customer->email) {
                Mail::to($customer->email)->send(new DiscountNotification($order));
            }
        }

        return redirect()->route('customer.order-history')->with('message', 'Order created successfully.');
    }

    // View receipt for a completed order
    public function viewReceipt($orderID)
    {
        $order = Order::with('orderServices.service', 'orderServices.measurement')
            ->where('orderID', $orderID)
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return redirect()->route('customer.order-history')->with('error', 'Receipt not available.');
        }

        return view('customer.receipt', compact('order'));
    }

    // View order details for a tailor
    public function viewOrderDetails($orderID)
    {
        $order = Order::with(['customer', 'orderServices.service', 'orderServices.measurement'])->findOrFail($orderID);

        return view('tailor.order-details', compact('order'));
    }
}
