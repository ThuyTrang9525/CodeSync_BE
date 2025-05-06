<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\AdminDashboardController;



Route::apiResource('student', StudentController::class);  // Sử dụng apiResource để tự động tạo các route


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('user', UserController::class);

Route::get('/dashboard', [AdminDashboardController::class, 'getStats']);
