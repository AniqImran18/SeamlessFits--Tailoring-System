@extends('layout.customer')

@section('title', 'Order Receipt')

@section('content')

<div class="container">
    <div class="text-center my-4">
        <!-- Display Company Logo at the top -->
        <img src="{{ asset('images/company-logo2.png') }}" alt="Company Logo" style="width: 190px; height: auto;">
        <h2 class="my-3">Order Receipt</h2>
    </div>

    <div class="card shadow-sm p-4">
        <!-- Display Receipt ID and Order Date -->
        <p><strong>Receipt ID:</strong> {{ $order->orderID }}</p>
        <p><strong>Date:</strong> {{ $order->date }}</p>
        <p><strong>Time:</strong> {{ $order->time }}</p>

        <!-- Display Customer Details and Service Details -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Name</th>
                    <th>Measurement Remark</th>
                    <th>Additional Remark</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0; // Initialize total amount
                @endphp
                @foreach($order->orderServices as $index => $orderService)
                    @php
                        $totalAmount += $orderService->service->price; // Add each service's price to the total
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $orderService->service->name }}</td>
                        <td>{{ $orderService->measurement->remark }}</td>
                        <td>{{ $orderService->additionalRemark ?? 'N/A' }}</td>
                        <td>RM{{ number_format($orderService->service->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Display Total Amount -->
        @php
        // Check if the order qualifies for a discount
        $orderCount = $order->customer->orders()->count();
        $isEligibleForDiscount = $orderCount % 5 === 0; // Only apply discount on 5th, 10th, 15th, etc., orders
        $discountRate = 0.1; // 10% discount
        $discountAmount = $isEligibleForDiscount ? $totalAmount * $discountRate : 0;
        $finalAmount = $totalAmount - $discountAmount;
        @endphp

        <p class="text-right">
            <strong>Total Amount:</strong> RM{{ number_format($totalAmount, 2) }}
        </p>

        @if($isEligibleForDiscount)
            <p class="text-right text-success">
                <strong>Discount (10%):</strong> -RM{{ number_format($discountAmount, 2) }}
            </p>
        @endif

        <p class="text-right">
            <strong>Amount to Pay:</strong> RM{{ number_format($finalAmount, 2) }}
        </p>

        
        <!-- Thank You Message at the Bottom -->
        <div class="text-center mt-4">
            <p>Thank you for choosing us!</p>
            <p><em>Your feedback is our strength.</em></p>
        </div>

        <!-- Download Button -->
        <div class="text-center mt-4">
            <button id="downloadButton" class="btn btn-primary" onclick="printReceipt()">Download as PDF</button>
        </div>
    </div>
</div>

<!-- Add JavaScript for print functionality -->
<script>
    function printReceipt() {
        // Hide the download button when printing
        document.getElementById('downloadButton').style.display = 'none';

        // Trigger print dialog
        window.print();

        // Show the download button again after printing
        document.getElementById('downloadButton').style.display = 'block';
    }
</script>

<!-- CSS to hide the download button when printing -->
<style>
    @media print {
        #downloadButton {
            display: none;
        }
    }
</style>

@endsection
