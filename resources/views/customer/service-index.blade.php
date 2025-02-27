@extends('layout.customer')

@section('title', 'Service List')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h5 mb-0">Service List</h1>
            
        </div>
        <div class="card-body">
            
            <form action="{{ route('customer.service-index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by service category or name" value="{{ $search }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @foreach($services as $category => $categoryServices)
                <h5 class="text-primary mt-4">{{ $category }}</h5>
                <table class="table table-hover table-borderless mt-2">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50%">Name</th>
                            <th style="width: 50%">Price</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoryServices as $service)
                            <tr>
                                <td class="align-middle">{{ $service->name }}</td>
                                <td class="align-middle">RM{{ number_format($service->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>

@endsection
