<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TeacherController;


Route::apiResource('/users', UserController::class);
Route::apiResource('/students', StudentController::class);
Route::apiResource('/teachers', TeacherController::class);


Route::post('/login', [UserController::class, 'login']);
Route::get('/stats', [AdminController::class, 'getStats']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/goals', [StudentController::class, 'getGoals']);
    Route::get('/goals/semesters', [StudentController::class, 'getSemesters']);
    Route::get('/goals/semesters/{semester}', [StudentController::class, 'getSemesterGoals']);
    Route::post('/goals', [StudentController::class, 'storeGoal']);
    Route::get('/goals/{goal}', [StudentController::class, 'showGoal']);
    Route::put('/goals/{goal}', [StudentController::class, 'updateGoal']);
    Route::delete('/goals/{goal}', [StudentController::class, 'destroyGoal']);
});
Route::middleware('auth:sanctum')->get('/teacher/classes', [TeacherController::class, 'getTeacherClasses']);

Route::prefix('admin')->group(function () {
    Route::get('users', [AdminController::class, 'indexUsers']);
    Route::get('users/{id}', [AdminController::class, 'showUser']);
    Route::post('users', [AdminController::class, 'storeUser']);
    Route::put('users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('users/{id}', [AdminController::class, 'destroyUser']);

    Route::get('classes', [AdminController::class, 'indexClasses']);
    Route::get('classes/{id}', [AdminController::class, 'showClass']);
    Route::post('classes', [AdminController::class, 'storeClass']);
    Route::put('classes/{id}', [AdminController::class, 'updateClass']);
    Route::delete('classes/{id}', [AdminController::class, 'destroyClass']);

    Route::get('stats', [AdminController::class, 'getStats']);
});