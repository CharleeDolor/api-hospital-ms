<?php

namespace App\Http\Controllers;

use App\Models\ArchivedRecord;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Record;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class DoctorsController extends Controller
{

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:doctors',
            'address' => 'required|max:150',
            "phone_number" => 'required',
            "medical_license" => 'required',
            "gender" => 'required',
            "medical_school_graduated" => 'required',
            "year_graduated" => 'required|integer|min:1980|max:'.date('Y'),
            "specialties" => 'required',
            "career_summary" => 'required|min:50'
        ]);

        return $validator->messages();
    }

    public function index()
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));

            if (in_array('view doctors', $permissions)) {
                $doctors = Doctor::all();

                return response()->json([
                    'doctors' => $doctors,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));

            if (in_array('view doctors', $permissions)) {
                $doctor = Doctor::where('id', $id)->firstOrFail();

                return response()->json([
                    'doctor' => $doctor
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('create doctors', $permissions)) {

                if ($this->validateRequest($request)) {
                    return response()->json([
                        'message' => $this->validateRequest($request)
                    ], 200);
                }

                $doctor = Doctor::create([
                    "name" => $request->name,
                    "email" => $request->email,
                    "address" => $request->address,
                    "phone_number" => $request->phone_number,
                    "medical_license" => $request->medical_license,
                    "gender" => $request->gender,
                    "medical_school_graduated" => $request->medical_school_graduated,
                    "year_graduated" => $request->year_graduated,
                    "specialties" => $request->specialties,
                    "career_summary" => $request->career_summary,
                ]);

                // check if email is already a user, if found then update user->details_id
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    $user->details_id = $doctor->id;
                    $user->save();
                } else {

                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'type' => 2,
                        'details_id' => $doctor->id,
                        // default password
                        'password' => Hash::make('doc2024_hms'),
                    ]);

                    $user->assignRole('doctor');
                }

                return response()->json([
                    'doctor' => $doctor,
                    'account_details' => $user
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again' . $th
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('edit doctors', $permissions)) {

                if ($this->validateRequest($request)) {
                    return response()->json([
                        'message' => $this->validateRequest($request)
                    ], 200);
                }

                $doctor = Doctor::where('id', $id)->firstOrFail();

                $doctor->name = $request->name;
                $doctor->email = $request->email;
                $doctor->address = $request->address;
                $doctor->phone_number = $request->phone_number;
                $doctor->medical_license = $request->medical_license;
                $doctor->gender = $request->gender;
                $doctor->medical_school_graduated = $request->medical_school_graduated;
                $doctor->year_graduated = $request->year_graduated;
                $doctor->specialties = $request->specialties;
                $doctor->career_summary = $request->career_summary;

                $doctor->save();

                return response()->json([
                    'message' => "Doctor details updated"
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again' . $th
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('delete doctors', $permissions)) {

                // inner join tables records, patients, and doctors
                $records = json_decode(Record::join('patients', 'records.patient_id', '=', 'patients.id')
                    ->join('doctors', 'records.doctor_id', '=', 'doctors.id')
                    ->select(
                        'records.date_of_consultation as date_of_consultation',
                        'patients.name as patient_name',
                        'patients.contact_number as patient_contact_number',
                        'patients.email as patient_email',
                        'records.diagnosis as diagnosis',
                        'records.recommendations as recommendations',
                        'doctors.name as doctor_name',
                        'doctors.phone_number as doctor_contact_number',
                        'doctors.email as doctor_email',
                    )
                    ->where('doctors.id', $id)
                    ->getQuery()
                    ->get(), true);

                // insert multi to archive records
                ArchivedRecord::insert($records);

                // delete doctor details and account
                $doctor = Doctor::where('id', $id)->firstOrFail();
                $user = User::where('email', $doctor->email)->firstOrFail();

                $user->delete();
                $doctor->delete();

                return response()->json([
                    'message' => 'Doctor details deleted'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again' . $th
            ], 500);
        }
    }
}
