<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Record;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class RecordsController extends Controller
{
    public function index(Request $request)
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('view records', $permissions)) {

                $current_id = auth('sanctum')->user()->details_id;

                if (auth('sanctum')->user()->getRoleNames()[0] == "patient") {
                    $records = Record::where('patient_id', $current_id)->first();
                } else if (auth('sanctum')->user()->getRoleNames()[0] == "doctor") {
                    $records = Record::where('patient_id', $current_id)->first();
                } else {
                    $records = Record::all();
                }
                
                if ($records == null) {
                    return response()->json([
                        'message' => $records
                    ], 200);
                }

                return response()->json([
                    'records' => $records,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Forbidden',
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again' . $th,
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // check permissions
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('view records', $permissions)) {
                $record = Record::where('id', $id)->firstOrFail();

                return response()->json([
                    'record' => $record
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

    public function store(Request $request)
    {
        try {
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('create records', $permissions)) {
                // remove appointment from current patient
                $appointment = Appointment::where('patient_id', $request->patient_id)->first();

                if (!$appointment) {
                    return response()->json([
                        'message' => 'Appointment not found'
                    ], 200);
                }

                $appointment->delete();

                $record = Record::create([
                    'patient_id' => $request->patient_id,
                    // this means that doctors can only create new medical records
                    'doctor_id' => auth('sanctum')->user()->details_id,
                    'diagnosis' => $request->diagnosis,
                    'date_of_consultation' => date('Y-m-d'),
                    'recommendations' => $request->recommendations
                ]);

                return response()->json([
                    'record' => $record
                ], 201);
            } else {
                return response()->json([
                    'message' => "Fobidden"
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again" . $th
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('edit records', $permissions)) {
                $record = Record::where('id', $id)->firstOrFail();

                $record->diagnosis = $request->diagnosis;
                $record->date_of_consultation = $request->date_of_consultation;
                $record->recommendations = $request->recommendations;

                $record->save();

                return response()->json([
                    'message' => 'Record has been updated'
                ]);
            } else {
                return response()->json([
                    'message' => "Fobidden"
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again" . $th
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $permissions = json_decode(auth('sanctum')->user()->getAllPermissions()->pluck('name'));
            if (in_array('delete records', $permissions)) {

                $record = Record::where('id', $id)->firstOrFail();
                $record->delete();

                return response()->json([
                    'message' => 'Record deleted'
                ], 200);
            } else {
                return response()->json([
                    'message' => "Fobidden"
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again" . $th
            ], 500);
        }
    }
}
