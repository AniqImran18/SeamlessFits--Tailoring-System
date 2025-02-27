@extends('layout.tailor')

@section('title', 'Measurement Details')

@section('content')

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-lg p-4">
        <div class="card-header bg-primary text-white rounded-top">
            <h2 class="h4 mb-0 text-center">Create Service</h2>
        </div>
        <div class="container py-5">
            <h1 class="mb-4">Create Service</h1>
            <form action="{{ route('tailor.serviceStore') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Service Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" step="0.01" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Add Service</button>
                <a href="{{ route('tailor.service-index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </form>
        </div>
    </div>
</div>

@endsection
