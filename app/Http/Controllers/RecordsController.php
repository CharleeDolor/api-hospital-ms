<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\type;

class RecordsController extends Controller
{
    private function validatePermission($request, $permission)
    {
        return $request->user()->can($permission);
    }

    private function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            'patient_id' => "sometimes|required|integer",
            "date_of_consultaion" => "sometimes|required",
            'diagnosis' => "required|min:10",
            'recommendations' => "required|min:10"
        ]);

        return $validator->messages();
    }

    public function index(Request $request)
    {
        try {
            if ($this->validateRequest($request, 'view records')) {
                $currentUserId = $request->user()->details_id;
                $userRole = $request->user()->roles()->pluck('name')->first();

                switch ($userRole) {
                    case 'patient':
                        $records = Record::join('patients', 'records.patient_id', '=', 'patients.id')
                            ->join('doctors', 'records.doctor_id', '=', 'doctors.id')
                            ->select(
                                'records.id as id',
                                'records.date_of_consultation',
                                'patients.name as patient_name',
                                'records.diagnosis',
                                'records.recommendations',
                                'doctors.name as doctor_name'
                            )->where('patients.id', $currentUserId)->get();
                        break;

                    case 'admin':
                        $records = Record::join('patients', 'records.patient_id', '=', 'patients.id')
                            ->join('doctors', 'records.doctor_id', '=', 'doctors.id')
                            ->select(
                                'records.id as id',
                                'records.date_of_consultation',
                                'patients.name as patient_name',
                                'records.diagnosis',
                                'records.recommendations',
                                'doctors.name as doctor_name'
                            )->get();
                        break;
                    case 'doctor':
                        $records = Record::join('patients', 'records.patient_id', '=', 'patients.id')
                            ->join('doctors', 'records.doctor_id', '=', 'doctors.id')
                            ->select(
                                'records.id as id',
                                'records.date_of_consultation',
                                'patients.name as patient_name',
                                'records.diagnosis',
                                'records.recommendations',
                                'doctors.name as doctor_name'
                            )->where('doctors.id', $currentUserId)->get();
                        break;

                    default:
                        $records = Record::all();
                        break;
                }

                return response()->json($records);
            } else {
                return response()->json([
                    'message' => 'You are not allowed here'
                ], 403);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again' . $th,
            ], 500);
        }
    }
    public function show(Request $request, $id)
    {
        try {
            if ($this->validateRequest($request, 'view records')) {
                $record = Record::join('patients', 'records.patient_id', '=', 'patients.id')
                    ->join('doctors', 'records.doctor_id', '=', 'doctors.id')
                    ->select(
                        'records.id as id',
                        'records.date_of_consultation',
                        'patients.name as patient_name',
                        'records.diagnosis',
                        'records.recommendations',
                        'doctors.name as doctor_name'
                    )->where('records.id', $id)->firstOrFail();

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
            if ($this->validatePermission($request, 'create records')) {
                if (count($this->validateRequest($request)) > 0) {
                    return response()->json([
                        'message' => $this->validateRequest($request)
                    ], 200);
                }

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
                    'message' => 'You are not allowed'
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
            if ($this->validatePermission($request, 'edit records')) {
                if (count($this->validateRequest($request)) > 0) {
                    return response()->json([
                        'errors' => $this->validateRequest($request)
                    ], 200);
                }

                $record = Record::where('id', $id)->firstOrFail();

                $record->diagnosis = $request->diagnosis;
                $record->date_of_consultation = $request->date_of_consultation;
                $record->recommendations = $request->recommendations;

                $record->save();

                return response()->json([
                    'message' => 'Record has been updated'
                ]);
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
            if ($this->validateRequest($request, 'delete records')) {
                $record = Record::where('id', $id)->firstOrFail();
                $record->delete();

                return response()->json([
                    'message' => 'Record deleted'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong. Please try again" . $th
            ], 500);
        }
    }
}
