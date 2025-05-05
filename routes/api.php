<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;

// API Routes cho Student
Route::apiResource('student', StudentController::class);  // Sử dụng apiResource để tự động tạo các route

/*
Test API 
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will be
| assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Routes cho User
Route::apiResource('user', UserController::class);  // Thay thế các route cũ cho user bằng apiResource
