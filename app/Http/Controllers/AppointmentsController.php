<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentsController extends Controller
{

    private function validatePermission($request, $permission)
    {
        return $request->user()->can($permission);
    }

    private function validateRequest()
    {
    }

    public function index(Request $request)
    {
        try {
            if ($this->validatePermission($request, 'view appointments')) {
                $current_user_id = auth('sanctum')->user()->details_id;

                switch (auth('sanctum')->user()->roles[0]->name) {
                    case "patient":
                        $appointments = json_decode(Appointment::join('patients', 'appointments.patient_id', '=', 'patients.id')
                            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                            ->select(
                                'appointments.id as id',
                                'appointments.type as type',
                                'appointments.queue_number as queue_number',
                                'appointments.day as day',
                                'appointments.patient_id as patient_id',
                                'doctors.name as doctors_name'
                            )
                            ->where('patients.id', $current_user_id)
                            ->getQuery()
                            ->get(), true);
                        break;

                    case "doctor":
                        $appointments = json_decode(Appointment::join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                            ->select(
                                'appointments.id as id',
                                'appointments.type as type',
                                'appointments.queue_number as queue_number',
                                'appointments.day as day',
                                'appointments.patient_id as patient_id',
                                'patients.name as patients_name'
                            )
                            ->where('doctors.id', $current_user_id)
                            ->getQuery()
                            ->get(), true);
                        break;

                    case "admin":
                        $appointments = json_decode(Appointment::join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                            ->select(
                                'appointments.id as id',
                                'appointments.type as type',
                                'appointments.queue_number as queue_number',
                                'appointments.day as day',
                                'appointments.patient_id as patient_id',
                                'patients.name as patients_name',
                                'doctors.name as doctors_name'
                            )
                            ->getQuery()
                            ->get(), true);
                        break;
                    default:
                        $appointments = 'You are not allowed here';
                        break;
                }

                return response()->json([
                    'appointments' => $appointments,
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

    public function show(Request $request, $id)
    {
        try {
            if ($this->validatePermission($request, 'view appointments')) {
                $appointment = Appointment::where('id', $id)->firstOrFail();

                return response()->json([
                    'appointment' => $appointment
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

    public function store(Request $request)
    {
        try {
            if ($this->validatePermission($request, 'create appointments')) {
                // query to appointments table to check duplicate appointment of a patient
                $appointment = Appointment::where('patient_id', auth('sanctum')->user()->details_id)->get();

                if (count($appointment) == 1) {
                    return response()->json([
                        'message' => 'Sorry, you have already an appointment.',
                    ], 200);
                }

                //maximum of 20 patients per day for doctors
                if (Appointment::where('doctor_id', $request->doctor_id)->max('queue_number') == '20') {
                    return response()->json([
                        'message' => 'Sorry, this doctor is currently not accepting appointments this day.',
                    ], 200);
                }

                $appointment = Appointment::create([
                    'type' => 'check-up',
                    'queue_number' => Appointment::where('doctor_id', $request->doctor_id)->where('day', $request->day)->max('queue_number') + 1,
                    'day' => $request->day,
                    'patient_id' => auth('sanctum')->user()->details_id,
                    'doctor_id' => $request->doctor_id
                ]);

                return response()->json([
                    'appointment' => $appointment
                ], 201);
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

    public function update(Request $request, $id)
    {
        try {
            if ($this->validatePermission($request, 'edit appointments')) {
                $appointment = Appointment::where('id', $id)->firstOrFail();

                $appointment->day = $request->day;
                $appointment->queue_number = Appointment::where('doctor_id', $request->doctor_id)->where('day', $request->day)->max('queue_number') + 1;
                $appointment->save();

                return response()->json([
                    'message' => 'Appointment updated'
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

    public function destroy(Request $request, $id)
    {
        try {
            if ($this->validatePermission($request, 'delete appointments')) {
                $appointment = Appointment::where('id', $id)->firstOrFail();

                $appointment->delete();

                return response()->json([
                    'message' => 'Appointment deleted'
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
}
