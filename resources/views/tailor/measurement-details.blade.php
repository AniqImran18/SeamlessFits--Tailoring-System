@extends('layout.tailor')

@section('title', 'Measurement Details')

@section('content')

<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0">Measurement Details</h2>
            </div>
        </div>
        
        <div class="card-body p-4">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Measurement Details Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Measurement ID</th>
                            <th>Length (inch)</th>
                            <th>Waist (inch)</th>
                            <th>Shoulder (inch)</th>
                            <th>Hip (inch)</th>
                            <th>Wrist (inch)</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($customer->measurements as $measurement)
                        <tr>
                            <td class="text-center"><span class="badge bg-secondary">#{{ $measurement->measurementID }}</span></td>
                            <td>{{ $measurement->length ?? '-' }}</td>
                            <td>{{ $measurement->waist ?? '-' }}</td>
                            <td>{{ $measurement->shoulder ?? '-' }}</td>
                            <td>{{ $measurement->hip ?? '-' }}</td>
                            <td>{{ $measurement->wrist ?? '-' }}</td>
                            <td>{{ $measurement->remark ?: 'No remarks' }}</td>
                            <td class="text-center">
                                <a href="{{ route('tailor.measurement-edit', $measurement->measurementID) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('tailor.measurement-delete', $measurement->measurementID) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this measurement?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No measurements available</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('tailor.measurement-list') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
