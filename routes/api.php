<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Api\TeacherController;


Route::apiResource('/users', UserController::class);
Route::apiResource('/students', StudentController::class);
Route::apiResource('/teachers', TeacherController::class);

Route::post('/login', [UserController::class, 'login']);
Route::get('/dashboard', [AdminDashboardController::class, 'getStats']);
