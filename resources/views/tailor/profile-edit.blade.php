@extends('layout.tailor')

@section('title', 'Edit Profile')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h5 mb-0">Edit Profile</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('tailor.profile.update', ['tailorID' => $tailor->tailorID]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="font-weight-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tailor->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="phone_number" class="font-weight-bold">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $tailor->phone_number) }}" required>
                </div>
                <div class="form-group">
                    <label for="profile_picture" class="font-weight-bold">Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control-file">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

@endsection
