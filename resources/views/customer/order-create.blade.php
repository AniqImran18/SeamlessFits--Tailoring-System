@extends('layout.customer')

@section('title', 'Create Order')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-0"></div>
        <div class="col">
            <h1 class="my-4">Create Order</h1>

            <form action="{{ route('customer.order-store') }}" method="POST">
                @csrf

                <div id="servicesContainer">
                    <!-- Initial Service Input Group -->
                    <div class="service-group">
                        <div class="form-group">
                            <label for="services[]">Service:</label>
                            <select name="services[]" class="form-control" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->serviceID }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="measurementRemarks[]">Measurement Remark:</label>
                            <select name="measurementRemarks[]" class="form-control" required>
                                @foreach($measurements as $measurement)
                                    <option value="{{ $measurement->measurementID }}">{{ $measurement->remark }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="additionalRemarks[]">Additional Remarks:</label>
                            <textarea name="additionalRemarks[]" class="form-control"></textarea>
                        </div>
                    </div>
                </div><br>

                <div class="form-group">
                    <button type="button" class="btn btn-success" id="addServiceButton">Add Another Service</button>
                </div><br>

                <button type="submit" class="btn btn-primary">Submit Order</button>
                <a href="{{ route('customer.order-history') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('addServiceButton').addEventListener('click', function () {
        const servicesContainer = document.getElementById('servicesContainer');

        // Clone the first service-group and clear inputs
        const serviceGroup = document.querySelector('.service-group').cloneNode(true);

        serviceGroup.querySelectorAll('select, textarea').forEach(element => {
            if (element.tagName === 'SELECT') {
                element.value = '';
            } else {
                element.value = '';
            }
        });

        servicesContainer.appendChild(serviceGroup);
    });
</script>

@endsection
