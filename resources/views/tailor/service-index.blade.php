@extends('layout.tailor')

@section('title', 'Service List')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h5 mb-0">Service List</h1>
            <a href="{{ route('tailor.service-create') }}" class="btn btn-sm btn-light text-primary">Create New Service</a>
        </div>
        <div class="card-body">
            
            <form action="{{ route('tailor.service-index') }}" method="GET" class="mb-4">
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
                            <th style="width: 25%">Price</th>
                            <th style="width: 25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoryServices as $service)
                            <tr>
                                <td class="align-middle">{{ $service->name }}</td>
                                <td class="align-middle">RM{{ number_format($service->price, 2) }}</td>
                                {{-- <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tailor.service-edit', $service->serviceID) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('tailor.destroy', $service->serviceID) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td> --}}
                                <td class="align-middle">
                                    <a href="{{ route('tailor.service-edit', $service->serviceID) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('tailor.destroy', $service->serviceID) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>

@endsection
