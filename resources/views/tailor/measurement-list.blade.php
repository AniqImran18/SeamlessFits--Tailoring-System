@extends('layout.tailor')

@section('title', 'Measurement List')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h5 mb-0">Measurement List</h1>
            <a href="{{ route('tailor.measurement-create') }}" class="btn btn-sm btn-light text-primary">Create New Measurement</a>
        </div>
        
        <div class="card-body">
            

            <form action="{{ route('tailor.measurement-list') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by customer name or ID" value="{{ $search }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <table class="table table-hover table-borderless">
                <thead class="thead-light">
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td class="align-middle">{{ $customer->customerID }}</td>
                            <td class="align-middle">{{ $customer->name }}</td>
                            <td class="align-middle">{{ $customer->phone_number }}</td>
                            <td class="align-middle">
                                <a href="{{ route('tailor.measurement-details', $customer->customerID) }}" class="btn btn-sm btn-info">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
