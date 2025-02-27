@extends('layout.customer')

@section('title', 'My Measurement')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h5 mb-0">My Measurements</h1>
        </div>
        
        <div class="card-body">
            @if($measurements->isEmpty())
                <div class="alert alert-info text-center">You have no measurements recorded.</div>
            @else

                <!-- Measurements Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle text-secondary mt-2">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 15%">Length</th>
                                <th class="text-center" style="width: 15%">Waist</th>
                                <th class="text-center" style="width: 15%">Shoulder</th>
                                <th class="text-center" style="width: 15%">Hip</th>
                                <th class="text-center" style="width: 15%">Wrist</th>
                                <th class="text-center" style="width: 25%">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($measurements as $measurement)
                                <tr>
                                    <td class="text-center align-middle">{{ $measurement->length }}</td>
                                    <td class="text-center align-middle">{{ $measurement->waist }}</td>
                                    <td class="text-center align-middle">{{ $measurement->shoulder }}</td>
                                    <td class="text-center align-middle">{{ $measurement->hip }}</td>
                                    <td class="text-center align-middle">{{ $measurement->wrist }}</td>
                                    <td class="text-center align-middle">{{ $measurement->remark }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Measurement Guide Image -->
                <div class="text-center mb-4 position-relative">
                    <img src="{{ asset('images/measurement3.jpg') }}" style="width: 1100px; max-width: 1100px; height: 400px;" class="img-fluid" alt="Measurement Guide" style="max-width: 300px;"><br>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
