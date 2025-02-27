@extends('layout.customer')

@section('title', 'Order History')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h5 mb-0">Order History</h1>
            <a href="{{ route('customer.order-create') }}" class="btn btn-sm btn-light text-primary">Create New Order</a>
        </div>
        
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="alert alert-info text-center">No orders found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-borderless mt-2">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th> <!-- Numbering Column -->
                                <th>Date</th>
                                <th>Time</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $index => $order)
                                <tr>
                                    <td class="align-middle">{{ $index + 1 }}</td> <!-- Numbering Order -->
                                    <td class="align-middle">{{ $order->date }}</td>
                                    <td class="align-middle">{{ $order->time }}</td>
                                    <td class="align-middle">
                                        @forelse($order->orderServices as $orderService)
                                            <p>
                                                <strong>{{ $orderService->service->name ?? 'Service not found' }}</strong><br>
                                                Measurement: {{ $orderService->measurement->remark ?? 'Measurement not found' }}<br>
                                                Remark: {{ $orderService->additionalRemark ?? 'N/A' }}
                                            </p>
                                        @empty
                                            <p>No services available for this order.</p>
                                        @endforelse
                                    </td>
                                    <td class="align-middle">{{ ucfirst($order->status) }}</td>
                                    <td class="align-middle">
                                        @if($order->status === 'completed')
                                            <a href="{{ route('customer.receipt', $order->orderID) }}" class="btn btn-sm btn-outline-secondary">View Receipt</a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
