@extends('layouts.user')

@section('content')
    <style>
        /*styles*/
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .error-container {

            margin-left: auto;
            margin-right: auto;
            position: absolute;
            top:0;
            right:0;
        }
        .create-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 100%;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.2); 
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

      
        .create-container .btn-success {
            width: 100%;
            padding: 15px;
            border-radius: 4px;
            font-size: 18px;
            box-shadow: rgba(45, 35, 66, 0.5) 0 2px 4px, rgba(45, 35, 66, 0.5) 0 7px 13px -3px, #D6D6E7 0 -3px 0 inset;
            background-color: #066886;
            border: none;
            color: white;
            cursor: pointer;
            border: 1px solid #000000;
            transition: all 0.3s ease; 
        }

        .create-container .btn-success:hover {
            background: linear-gradient(to bottom, #04bade, white);
            color: black;
            border: 1px solid #0077B6;
            transform: scale(1.1);
            border-radius: 5rem;
        }

        .create-container a {
            margin-top: 15px;
            display: block;
            color: #000000;
            font-weight: bold;
            text-decoration: none;
            
        }
        .create-container a:hover {
            text-decoration: underline;
            transform: scale(1.1);
            color: #000000;
        }

        .h1, h1 {
            font-size: 2.5rem;
            color: #ffffff;
        }

    </style>
    <div class="error-container w-25 m-3">
        @if ($errors->any())
                {!! implode(
                    '',
                    $errors->all("
                                <div class='alert alert-danger alert-dismissible fade show m-3'>
                                    <p><strong>:message</strong>
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                "),
                ) !!}
        @endif
    </div>

    <div class="d-flex align-items-center justify-content-center min-vh-100 flex-column">
        
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="create-container">
            <div class="d-flex align-items-center justify-content-center flex-column p-lg-5">
                <h1>Create User</h1>
                @csrf

                <div class="form-group">
                    <input type="text" name="name" id="name" class="form-control" required placeholder="Name"
                        value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        id="email" aria-describedby="emailHelp" placeholder="Enter email" 
                        value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Password"
                        name="password" required autocomplete="current-password" 
                        value="{{ old('password') }}">
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Confirm Password"
                        name="password_confirmation" required autocomplete="current-password" 
                        value="{{ old('password_confirmation') }}">
                </div>


                <hr class="w-100 bg-dark" />
                <div class="form-group">
                    <input type="submit" value="Create Account" class="btn btn-success">
                </div>

                <div class="form-group">
                    <a href="{{ route('user.login') }}">Already have an account?</a>
                </div>
            </div>

        </form>
    </div>
