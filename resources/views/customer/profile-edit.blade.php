@extends('layout.customer')

@section('title', 'Edit Profile')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm p-4 border-0">
        <h1 class="h4 mb-4 text-primary font-weight-bold">Edit Profile</h1>
        <form action="{{ route('customer.profile.update', ['customerID' => $customer->customerID]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label font-weight-bold">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label font-weight-bold">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $customer->phone_number) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label font-weight-bold">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
            </div>
            <div class="mb-4">
                <label for="profile_picture" class="form-label font-weight-bold">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                @if($customer->profile_picture)
                    <img src="{{ asset('storage/' . $customer->profile_picture) }}" alt="Profile Picture" class="img-thumbnail mt-3" style="width: 150px;">
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100 font-weight-bold">Update Profile</button>
        </form>
    </div>
</div>
@endsection
