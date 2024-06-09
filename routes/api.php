<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// user authentication routes
Route::post('/login', [AuthController::class, 'login']);

// route for creating account
Route::post('/register', [UserController::class, 'store']);

// route for getting all count of rows
Route::get('/count', [AuthController::class, 'getAllCount'])->middleware('auth:sanctum');

// route logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// route get role/permissions
Route::get('/user/permissions', [AuthController::class, 'getPermissions'])->middleware('auth:sanctum');

// route get user information
Route::get('/user/information', [UserController::class, 'getUserInformation'])->middleware('auth:sanctum');

// crud patients
Route::get('/patients', [PatientsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/patients/{id}', [PatientsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/patients', [PatientsController::class, 'store'])->middleware('auth:sanctum');
Route::put('/patients/{id}', [PatientsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/patients/{id}', [PatientsController::class, 'destroy'])->middleware('auth:sanctum');

// crud doctors
Route::get('/doctors', [DoctorsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/doctors/{id}', [DoctorsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/doctors', [DoctorsController::class, 'store'])->middleware('auth:sanctum');
Route::put('/doctors/{id}', [DoctorsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/doctors/{id}', [DoctorsController::class, 'destroy'])->middleware('auth:sanctum');

// crud appointments
Route::get('/appointments', [AppointmentsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/appointments/{id}', [AppointmentsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/appointments', [AppointmentsController::class, 'store'])->middleware('auth:sanctum');
Route::put('/appointments/{id}', [AppointmentsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/appointments/{id}', [AppointmentsController::class, 'destroy'])->middleware('auth:sanctum');

//crud records
Route::get('/records', [RecordsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/records/{id}', [RecordsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/records', [RecordsController::class, 'store'])->middleware('auth:sanctum');
Route::put('/records/{id}', [RecordsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/records/{id}', [RecordsController::class, 'destroy'])->middleware('auth:sanctum');
