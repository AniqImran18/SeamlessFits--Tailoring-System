@extends('layout.tailor')

@section('title', 'Order Details')

@section('content')

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Order Details</h2>
                <span class="badge bg-light text-primary px-3 py-2">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Order Information Section -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <h5 class="text-muted">Order Information</h5>
                    <p><strong>Order ID:</strong> #{{ $order->orderID }}</p>
                    <p><strong>Order Created:</strong> {{ $order->created_at->format('H:i, d M Y') }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5 class="text-muted">Customer Information</h5>
                    <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                </div>
            </div>

            <!-- Services and Measurements Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="text-muted">Services and Measurements</h5>
                    @if($order->orderServices->isEmpty())
                        <p class="text-muted">No services or measurements available for this order.</p>
                    @else
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Length (inch)</th>
                                    <th>Waist (inch)</th>
                                    <th>Shoulder (inch)</th>
                                    <th>Hip (inch)</th>
                                    <th>Wrist (inch)</th>
                                    <th>Measurement Remark</th>
                                    <th>Additional Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderServices as $orderService)
                                    <tr>
                                        <td>{{ $orderService->service->name ?? 'Service not found' }}</td>
                                        <td>{{ $orderService->measurement->length ?? '-' }}</td>
                                        <td>{{ $orderService->measurement->waist ?? '-' }}</td>
                                        <td>{{ $orderService->measurement->shoulder ?? '-' }}</td>
                                        <td>{{ $orderService->measurement->hip ?? '-' }}</td>
                                        <td>{{ $orderService->measurement->wrist ?? '-' }}</td>
                                        <td>{{ $orderService->measurement->remark ?? 'No remark provided' }}</td>
                                        <td>{{ $orderService->additionalRemark ?? 'No additional remark provided' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Order Actions Section -->
            <div class="text-center">
                @if($order->status === 'pending')
                    <form action="{{ route('tailor.order-accept', $order->orderID) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-check-circle me-2"></i>Accept Order</button>
                    </form>
                @elseif($order->status === 'in_process')
                    <form action="{{ route('orders.complete', $order->orderID) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-clipboard-check me-2"></i>Mark as Complete</button>
                    </form>
                @else
                    <p class="text-success fw-bold mt-3"><i class="fas fa-check-circle"></i> This order is complete.</p>
                @endif
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('tailor.order-pending') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Orders</a>
            </div>
        </div>
    </div>
</div>

@endsection
