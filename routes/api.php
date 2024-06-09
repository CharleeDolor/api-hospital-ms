<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// crud patients
Route::get('/patients', [PatientsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/patients/{id}', [PatientsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/patients', [PatientsController::class, 'store'])->middleware('auth:sanctum');
Route::put('/patients/{id}', [PatientsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/patients/{id}', [PatientsController::class, 'destroy'])->middleware('auth:sanctum');


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