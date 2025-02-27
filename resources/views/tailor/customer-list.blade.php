@extends('layout.tailor')

@section('title', 'Customer List')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">Customer List</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('tailor.customer-list') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </div>
                </div>
            </form>

            <table class="table table-hover table-borderless">
                <thead class="thead-light">
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="align-middle">{{ $customer->customerID }}</td>
                        <td class="align-middle">{{ $customer->name }}</td>
                        <td class="align-middle">{{ $customer->phone_number }}</td>
                        <td class="align-middle">{{ $customer->email }}</td>
                        <td class="align-middle">
                            <a href="{{ route('tailor.customer-details', $customer->customerID) }}" class="btn btn-sm btn-info">View Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
