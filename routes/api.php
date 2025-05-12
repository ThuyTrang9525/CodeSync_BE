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

// Goal routes - protected by authentication
Route::middleware('auth:sanctum')->group(function () {
    // Get all goals with optional semester filter
    Route::get('/goals', [StudentController::class, 'getGoals']);
    
    // Get list of all semesters
    Route::get('/goals/semesters', [StudentController::class, 'getSemesters']);
    
    // Get goals for a specific semester
    Route::get('/goals/semesters/{semester}', [StudentController::class, 'getSemesterGoals']);
    
    // Standard CRUD routes for goals
    Route::post('/goals', [StudentController::class, 'storeGoal']);
    Route::get('/goals/{goal}', [StudentController::class, 'showGoal']);
    Route::put('/goals/{goal}', [StudentController::class, 'updateGoal']);
    Route::delete('/goals/{goal}', [StudentController::class, 'destroyGoal']);
});