<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://img.freepik.com/premium-photo/open-book-with-graduation-hat-light-bulb-education-learning-school-university-idea-concept-3d-illustration_56345-753.jpg?w=826');
            background-size: cover;
            background-position: center;
            z-index: -1;
            filter: blur(3px); 
        }
        .background-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            z-index: -1;
        }
        .login-link {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: rgba(45, 35, 66, 1) 0 2px 4px, rgba(45, 35, 66, 1) 0 7px 13px -3px, #D6D6E7 0 -3px 0 inset;
            background: linear-gradient(to bottom, #C8A165, #A67853);
            border: none;
            color: black;
            cursor: pointer;
            border: 1px solid #000000;
            position: absolute;
            top: 385px;
            left: 50%;
            transform: translateX(-50%);
            transition: all 0.3s ease; 
        }

         .login-link:hover {
            background: linear-gradient(to bottom, #A67853, white); /* Adjusted to white brown tones */
            color: black;
            border: 1px solid #A67853;
            box-shadow: rgba(45, 35, 66, 0.4) 0 2px 4px, rgba(45, 35, 66, 0.3) 0 7px 13px -3px, #D6D6E7 0 -3px 0 inset;
            transform: translateX(-50%) scale(1.1);
            border-radius: 5rem;
        }

    </style>
</head>
<body>

<div class="background-image"></div>
<a href="{{ route('user.login') }}" class="login-link">Click to Start</a>

</body>
</html>
