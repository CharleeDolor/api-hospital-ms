<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $data =  $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = User::where('email', $data['email'])->first();

                if ($user->type == 1) {
                    $token = $user->createToken('user', ['admin'])->plainTextToken;
                }

                if ($user->type == 2) {
                    $token = $user->createToken('user', ['doctor'])->plainTextToken;
                }

                if ($user->type == 3) {
                    $token = $user->createToken('user', ['patient'])->plainTextToken;
                }

                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['message' => 'wrong credentials'], 401);
            }

            
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->guard('web')->logout();
            $accessToken = $request->bearerToken();
            $token = PersonalAccessToken::findToken($accessToken);
            $token->delete();

            return response()->json(['message' => 'Logged out'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Server Error: Something went wrong. ' . $th], 500);
        }
    }

    public function getPermissions(Request $request)
    {
        return response()->json([
            'roles' => $request->user()->getRoleNames()->first(),
            'permissions' => $request->user()->getAllPermissions()->pluck('name'),
        ]);
    }

    public function getAllCount()
    {
        switch (auth('sanctum')->user()->roles[0]->name) {
            case 'admin':
                $payload = [
                    'patients' => Patient::count(),
                    'doctors' => Doctor::count(),
                    'appointments' => Appointment::count(),
                    'records' => Record::count()
                ];
                break;

            case 'patient':
                $payload = [
                    'doctors' => Doctor::count(),
                    'appointments' => Appointment::where('patient_id', auth('sanctum')->user()->details_id)->count(),
                    'records' => Record::where('patient_id', auth('sanctum')->user()->details_id)->count()
                ];
                break;

            case 'doctor':
                $payload = [
                    'appointments' => Appointment::where('doctor_id', auth('sanctum')->user()->details_id)->count(),
                    'records' => Record::where('doctor_id', auth('sanctum')->user()->details_id)->count()
                ];
                break;
        }

        return response()->json([
            'count' => $payload
        ]);
    }
}
