@extends('layout.tailor')

@section('title', 'Measurement Details')

@section('content')

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-lg p-4">
        <div class="card-header bg-primary text-white rounded-top">
            <h2 class="h4 mb-0 text-center">Measurement Details</h2>
        </div>

        <!-- Display validation errors -->
        @if($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form to create a new measurement -->
        <form action="{{ route('tailor.store') }}" method="POST" class="mt-4">
            @csrf

            <div class="form-group mb-3">
                <label for="customerID" class="font-weight-bold">Select Customer</label>
                <select name="customerID" id="customerID" class="form-control" required>
                    <option value="" disabled selected>-- Select Customer --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->customerID }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="length" class="font-weight-bold">Length (inch)</label>
                    <input type="number" name="length" class="form-control" placeholder="Enter length" step="0.01" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="waist" class="font-weight-bold">Waist (inch)</label>
                    <input type="number" name="waist" class="form-control" placeholder="Enter waist measurement" step="0.01" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="shoulder" class="font-weight-bold">Shoulder (inch)</label>
                    <input type="number" name="shoulder" class="form-control" placeholder="Enter shoulder measurement" step="0.01" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="hip" class="font-weight-bold">Hip   (inch)</label>
                    <input type="number" name="hip" class="form-control" placeholder="Enter hip measurement" step="0.01" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="wrist" class="font-weight-bold">Wrist (inch)</label>
                    <input type="number" name="wrist" class="form-control" placeholder="Enter wrist measurement" step="0.01" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="remark" class="font-weight-bold">Remark (*any special instruction or note)</label>
                    <input type="text" name="remark" class="form-control" placeholder="Additional notes" required>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary mr-2">Save Measurement</button>
                <a href="{{ route('tailor.measurement-list') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
