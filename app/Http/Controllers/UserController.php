<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
            ];
            $custom_messages = [
                [
                    'email.unique' => 'The email has already been taken.',
                    'password.min' => 'The password must be at least 6 characters.',
                    'password.confirmed' => 'The password confirmation does not match.',
                    'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
                ]
            ];

            $request->validate($rules, $custom_messages);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'type' => $request->type,
                'password' => Hash::make($request->password),
            ]);

            if($request->type == 2) {
                $user->assignRole('doctor');
            }

            if($request->type == 3){
                $user->assignRole('patient');
            }
            
            return response()->json([
                'message' => 'Account created',
                'method' => 'POST'
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUserInformation(){
        return response()->json([
            "account" => auth('sanctum')->user()
        ]);
    }

}
