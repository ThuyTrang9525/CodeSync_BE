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
    Route::get('/my-classes', [StudentController::class, 'getStudentClasses']);
    // Get all class groups for student
    // Route::get('/my-classes', [StudentController::class, 'getStudentClasses']);
    // Get all notifications for student
    // Route::get('/notifications/{receiverID}', [StudentController::class, 'getNotificationsByUser']);
    // Route::delete('/notifications/{notificationID}', [StudentController::class, 'deleteNotification']);
    // Route::post('/notifications/{notificationID}/read', [StudentController::class, 'markAsRead']);
});

 Route::delete('/student/notifications/{notificationID}', [StudentController::class, 'deleteNotification']);
 Route::post('/student/notifications/{notificationID}/read', [StudentController::class, 'markAsRead']);
 Route::get('/student/notifications/{receiverID}', [StudentController::class, 'getNotificationsByUser']);

Route::middleware('auth:sanctum')->get('/teacher/classes', [TeacherController::class, 'getTeacherClasses']);
Route::get('/classes/{classId}/students', [TeacherController::class, 'getStudentsByClass']);
Route::get('/notifications/{receiverID}', [TeacherController::class, 'getNotificationsByUser']);

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
    Route::get('goals', [AdminController::class, 'getGoals']);
    Route::middleware('auth:api')->post('logout', [AdminController::class, 'logout']);

    Route::patch('/notifications/{id}/mark-read', [AdminController::class, 'markRead']);
    Route::delete('/notifications/{id}', [AdminController::class, 'destroy']);
    Route::post('/notifications/{id}/read', [AdminController::class, 'markAsRead']);

    Route::get('notifications', [AdminController::class, 'getNotifications']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/student/study-plans/semester/{semester}', [StudentController::class, 'getStudyPlansBySemester']);
    Route::post('/student/study-plans', [StudentController::class, 'createStudyPlan']);
    Route::put('/student/study-plans/{id}', [StudentController::class, 'updateStudyPlan']);
    Route::delete('/student/study-plans/{id}', [StudentController::class, 'deleteStudyPlan']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Self Study Plan APIs
    Route::get('/student/inclass-plans/semester/{semester}', [StudentController::class, 'getSelfPlansBySemester']);
    Route::get('/student/inclass-plans/{id}', [StudentController::class, 'getSelfPlan']);
    Route::post('/student/inclass-plans', [StudentController::class, 'createSelfPlan']);
    Route::put('/student/inclass-plans/{id}', [StudentController::class, 'updateSelfPlan']);
    Route::delete('/student/inclass-plans/{id}', [StudentController::class, 'deleteSelfPlan']);
    // Study Plans by Semester & Week
Route::get('/student/study-plans/semester/{semester}/week/{week}', [StudentController::class, 'getStudyPlansBySemesterAndWeek']);

// In-Class Plans by Semester & Week
Route::get('/student/inclass-plans/semester/{semester}/week/{week}', [StudentController::class, 'getSelfPlansBySemesterAndWeek']);
Route::get('/my-classes', [StudentController::class, 'getStudentClasses']);

});
