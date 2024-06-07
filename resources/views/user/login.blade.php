@extends('layouts.user')

@section('content')
    <style>
        body {
            background: linear-gradient(to bottom, #007BFF, #FFFFFF);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://i.pinimg.com/originals/65/79/4d/65794da1ce3e5e3537a281ad6eaba286.jpg');
            background-size: cover;
            background-position: center;
            z-index: -1;
            overflow: hidden;
            &::before {
                content: '';
                position: absolute;
                top: 0;
                 left: 0;
                width: 100%;
                height: 100%; 
                background-color: rgba(255, 255, 255, 0.2); 
                z-index: -1;
                }
            }


        .inputType {
            height: 50px;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
        }

        .error-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto 20px auto;
            padding: 0 15px;
        }

        .alert {
            margin-bottom: 20px;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            padding: 60px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin-left: 700px; 
            text-align: center;
            background-color: rgba(255, 255, 255, 0.2); 
        }

        .login-form h1 {
            margin-bottom: 30px;
            color: #FFFFFF;
        }

        .login-form a {
            margin-top: 15px;
            display: block;
            color: #000000;
            font-weight: bold;
            text-decoration: none;
        }
        
        
        .login-form a:hover {
            text-decoration: underline;
            transform: scale(1.1); 
             color: black; 
             
        }

        .btn-success {
            width: 80%;
            padding: 10px;
            border-radius: 15px;
            font-size: 18px;
            box-shadow: rgba(45, 35, 66, 0.5) 0 2px 4px, rgba(45, 35, 66, 0.5) 0 7px 13px -3px, #D6D6E7 0 -3px 0 inset;
            background-color: #066886;
            border: none;
            color: white;
            cursor: pointer;
            border: 1px solid #000000;
            transition: all 0.3s ease; 
        }

        .btn-success:hover {
            background: linear-gradient(to bottom, #04bade, white);
            color: black;
            border: 1px solid #0077B6;
            transform: scale(1.1);
            border-radius: 5rem;
        }

        .close {
            background: none;
            border: none;
            font-size: 1.5rem;
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
        }

        .fade {
            opacity: 1;
            transition: opacity 0.15s linear;
        }

        .show {
            display: block;
        }

        .alert-dismissible {
            position: relative;
        }
    </style>

    <div class="error-container">
        @if (\Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" id="success-alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! \Session::get('success') !!}</strong> Login using your email and password
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <span><strong>{{ $message }}</strong></span>
            </div>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <span><strong>{{ $error }}</strong></span>
                </div>
            @endforeach
        @endif
    </div>

    <div class="login-container">
        <form action="{{ route('auth.doLogin') }}" method="POST" class="login-form">
            <h1>Login</h1>
            @csrf
            <input type="email" class="inputType" name="email" id="email" placeholder="Email address" value="{{ old('email') }}" required>
            <input type="password" class="inputType" name="password" id="password" placeholder="Password" required>
            <input type="submit" value="Login" class="btn btn-success">
            <a href="{{ route('user.create') }}">Create an account</a>
        </form>
    </div>
@endsection
