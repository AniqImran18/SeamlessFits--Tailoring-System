<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            height: 120vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-container {
            display: flex;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .register-image {
            flex: 1;
            background: url('{{ asset('images/company-logo2.png') }}') no-repeat center center;
            background-size: 60%; /* Adjusted logo size */
            background-color: #f8f9fa; /* Added background color for better visibility */
            min-height: 400px;
        }
        .register-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #333;
            background-color: #fff;
        }
        .form-control {
            border-radius: 50px;
        }
        .btn-custom {
            border-radius: 50px;
            background: #6a11cb;
            border: none;
            color: white;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #2575fc;
            color: white;
        }
        .register-footer {
            margin-top: 20px;
            font-size: 14px;
        }
        .register-footer a {
            color: #6a11cb;
            text-decoration: none;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <!-- Side Image -->
            <div class="register-image"></div>

            <!-- Registration Form -->
            <div class="register-form">
                <h2 class="text-center mb-4">Customer Registration</h2>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('customer.register.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
                        <small class="text-danger">* Name should only contain letters and spaces (no numbers allowed).</small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Create a password" required>
                        <small class="text-danger">* Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" class="form-control-file" name="profile_picture" id="profile_picture" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-custom btn-block">Register</button>
                </form>
                
                <div class="register-footer text-center">
                    <p>Already have an account? <a href="{{ route('customer.login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>