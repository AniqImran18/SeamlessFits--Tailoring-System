@extends('layout.tailor')

@section('title', 'Orders')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">Orders</h1>
        </div>
        <div class="card-body">
            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            {{-- Pending / In-Process Orders --}}
            <h2 class="h6">Pending / In-Process Orders</h2>
            @if($pendingOrders->isEmpty())
                <p>No pending or in-process orders at the moment.</p>
            @else
                <table class="table table-hover table-borderless mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th>Order Created</th>
                            <th>Customer Name</th>
                            <th>Services</th>
                            <th>Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingOrders as $order)
                            <tr>
                                <td class="align-middle">{{ $order->created_at->format('H:i, d M Y') }}</td>
                                <td class="align-middle">{{ $order->customer->name }}</td>
                                <td class="align-middle">
                                    @foreach($order->orderServices as $orderService)
                                        <p>{{ $orderService->service->name }}</p>
                                    @endforeach
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('tailor.order-details', $order->orderID) }}" class="btn btn-sm btn-info">View Details</a>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        @if($order->status === 'pending')
                                            <form action="{{ route('tailor.order-accept', $order->orderID) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                            </form>
                                        @elseif($order->status === 'in_process')
                                            <form action="{{ route('orders.complete', $order->orderID) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">Complete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Completed Orders --}}
            <h2 class="h6 mt-5">Completed Orders</h2>
            @if($completeOrder->isEmpty())
                <p>No completed orders at the moment.</p>
            @else
                <table class="table table-hover table-borderless mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th>Order Completed</th>
                            <th>Customer Name</th>
                            <th>Services</th>
                            <th>Details</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completeOrder as $order)
                            <tr>
                                <td class="align-middle">{{ $order->updated_at->format('H:i, d M Y') }}</td>
                                <td class="align-middle">{{ $order->customer->name }}</td>
                                <td class="align-middle">
                                    @foreach($order->orderServices as $orderService)
                                        <p>{{ $orderService->service->name }}</p>
                                    @endforeach
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('tailor.order-details', $order->orderID) }}" class="btn btn-sm btn-info">View Details</a>
                                </td>
                                <td class="align-middle">{{ ucfirst($order->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection
