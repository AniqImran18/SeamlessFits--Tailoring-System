@extends('layout.tailor')

@section('title', 'Edit Measurement')

@section('content')

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Edit Measurement</h2>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Display Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form to Update Measurement Data -->
            <form action="{{ route('tailor.measurement-update', $measurement->measurementID) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="length" class="form-label">Length (in)</label>
                        <input type="number" name="length" class="form-control" value="{{ $measurement->length }}" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="waist" class="form-label">Waist (in)</label>
                        <input type="number" name="waist" class="form-control" value="{{ $measurement->waist }}" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="shoulder" class="form-label">Shoulder (in)</label>
                        <input type="number" name="shoulder" class="form-control" value="{{ $measurement->shoulder }}" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="hip" class="form-label">Hip (in)</label>
                        <input type="number" name="hip" class="form-control" value="{{ $measurement->hip }}" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label for="wrist" class="form-label">Wrist (in)</label>
                        <input type="number" name="wrist" class="form-control" value="{{ $measurement->wrist }}" step="0.01" required>
                    </div>
                    <div class="col-md-12">
                        <label for="remark" class="form-label">Remark</label>
                        <input type="text" name="remark" class="form-control" value="{{ $measurement->remark }}" placeholder="Add any remarks here (optional)">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg me-2"><i class="fas fa-save"></i> Update Measurement</button>
                    <a href="{{ route('tailor.measurement-details', ['customerID' => $measurement->customerID]) }}" class="btn btn-secondary btn-lg"><i class="fas fa-times-circle"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
