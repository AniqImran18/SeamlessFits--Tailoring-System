@extends('layout.tailor')

@section('title', 'Customer Details')

@section('content')
<div class="container my-5 p-4" style="background: linear-gradient(135deg, #f9f9f9, #e6f7ff); border-radius: 10px;">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center rounded-top d-flex align-items-center" style="padding: 15px;">
                    <h4 class="mb-0">Customer Details</h4>
                    <p class="ml-auto mb-0"><i class="fas fa-scissors"></i> Orchid Tailoring</p>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <!-- Customer ID Section -->
                            <div class="mb-3 d-flex align-items-center" style="background-color: #e0f7fa; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-id-badge text-primary mr-3" style="font-size: 1.5em;"></i>
                                <div>
                                    <p class="mb-1 font-weight-bold text-primary">Customer ID:</p>
                                    <p class="text-muted mb-0">{{ $customer->customerID }}</p>
                                </div>
                            </div>

                            <!-- Name Section -->
                            <div class="mb-3 d-flex align-items-center" style="background-color: #e3f2fd; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-user text-info mr-3" style="font-size: 1.5em;"></i>
                                <div>
                                    <p class="mb-1 font-weight-bold text-info">Name:</p>
                                    <p class="text-muted mb-0">{{ $customer->name }}</p>
                                </div>
                            </div>

                            <!-- Phone Number Section -->
                            <div class="mb-3 d-flex align-items-center" style="background-color: #e1f5fe; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-phone-alt text-success mr-3" style="font-size: 1.5em;"></i>
                                <div>
                                    <p class="mb-1 font-weight-bold text-success">Phone Number:</p>
                                    <p class="text-muted mb-0">{{ $customer->phone_number }}</p>
                                </div>
                            </div>

                            
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3 d-flex align-items-center" style="background-color: #f1f8e9; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-map-marker-alt text-danger mr-3" style="font-size: 1.5em;"></i>
                                <div>
                                    <p class="mb-1 font-weight-bold text-warning">Email:</p>
                                    <p class="text-muted mb-0">{{ $customer->email }}</p>
                                </div>
                            </div>

                            <!-- Order History Section -->
                            <div class="mb-3 d-flex align-items-center" style="background-color: #ffebee; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-list-alt text-danger mr-3" style="font-size: 1.5em;"></i>
                                <div>
                                    <p class="mb-1 font-weight-bold text-danger">Total Orders:</p>
                                    <p class="text-muted mb-0">{{ $customer->orders_count }} orders</p> <!-- Displays the actual count of orders -->
                                </div>
                            </div>

                            
                        </div>
                        <div class="text-center mt-4">
                            {{-- <form action="{{ route('tailor.customer.destroy', $customer->customerID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Customer
                                </button>
                            </form> --}}
                            <a href="{{ route('tailor.customer-list') }}" class="btn btn-secondary mt-2" style="background-color: #00aaff; border: none;">
                                <i class="fas fa-arrow-left"></i> Back to Customer List
                            </a>
                        </div>
                {{-- <!-- Button Row -->
                <div class="text-center mt-4">
                    <a href="{{ route('tailor.customer-list') }}" class="btn btn-secondary" style="background-color: #00aaff; border: none;">
                        <i class="fas fa-arrow-left"></i> Back to Customer List

                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
