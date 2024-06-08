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

// crud records
Route::get('/records', [RecordsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/records/{id}', [RecordsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/records', [RecordsController::class, 'store'])->middleware('auth:sanctum');
Route::put('/records/{id}', [RecordsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/records/{id}', [RecordsController::class, 'destroy'])->middleware('auth:sanctum');