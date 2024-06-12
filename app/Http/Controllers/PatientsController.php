<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class PatientsController extends Controller
{
    private function validatePermission($request, $permission)
    {
        return $request->user()->can($permission);
    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'sometimes|required|email|unique:patients',
            'birthday' => 'required|date_format:Y-m-d|before:today',
            'address' => 'required|max:150',
            "gender" => 'required',
            "marital_status" => 'required',
            "contact_number" => 'required',
            "blood_type" => 'required',
            "weight" => 'required|numeric|min:1',
            "height" => 'required|numeric|min:1'
        ]);

        return $validator->messages();
    }

    public function index(Request $request)
    {
        if ($this->validatePermission($request, 'view patients')) {
            $patients = Patient::all();

            return response()->json([
                'patients' => $patients
            ], 200);
        } else {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }
    }

    public function store(Request $request)
    {
        if ($this->validatePermission($request, 'create patients')) {
            if (count($this->validateRequest($request)) > 0) {
                return response()->json([
                    'message' => $this->validateRequest($request)
                ], 200);
            }

            $patient = Patient::create([
                "name" => $request->name,
                "email" => $request->email,
                "address" => $request->address,
                "birthday" => $request->birthday,
                "age" => floor(abs(strtotime(date('Y-m-d')) - strtotime($request->birthday)) / (365 * 60 * 60 * 24)),
                "gender" => $request->gender,
                "marital_status" => $request->marital_status,
                "contact_number" => $request->contact_number,
                "blood_type" => $request->blood_type,
                "weight" => $request->weight,
                "height" => $request->height
            ]);

            // check if email is already a user, if found then update user->details_id
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->details_id = $patient->id;
                $user->save();
            } else {

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'type' => 3,
                    'details_id' => $patient->id,
                    'password' => Hash::make('outpatient_2024'),
                ]);

                $user->assignRole('patient');
            }

            return response()->json([
                'patient' => $patient,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }
    }

    public function show($id)
    {
        $patient = Patient::where('id', $id)->firstOrFail();

        return response()->json([
            'patient' => $patient
        ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            if ($this->validatePermission($request, 'edit patients')) {
                if (count($this->validateRequest($request)) > 0) {
                    return response()->json([
                        'errors' => $this->validateRequest($request)
                    ], 200);
                }

                $patient = Patient::where('id', $id)->firstOrFail();

                $patient->name = $request->name;
                $patient->address = $request->address;
                $patient->birthday = $request->birthday;
                $patient->age = floor(abs(strtotime(date('Y-m-d')) - strtotime($request->birthday)) / (365 * 60 * 60 * 24));
                $patient->gender = $request->gender;
                $patient->marital_status = $request->marital_status;
                $patient->contact_number = $request->contact_number;
                $patient->blood_type = $request->blood_type;
                $patient->weight = $request->weight;
                $patient->height = $request->height;

                $patient->save();

                return response()->json([
                    'message' => "Patient details updated"
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again" . $th
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            if ($this->validatePermission($request, 'delete patients')) {
                $patient = Patient::where('id', $id)->firstOrFail();
                $user = User::where('email', $patient->email)->firstOrFail();

                $user->delete();
                $patient->delete();

                return response()->json([
                    'message' => "Patient deleted"
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again"
            ], 500);
        }
    }
}
