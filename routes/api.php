<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\TeacherController;
use App\Models\Teacher;

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
    // profile student đã đăng nhập
    Route::get('/student/profile', [StudentController::class, 'getProfile']);
    //cập nhật profile
    Route::put('/student/profile/{userID}', [StudentController::class, 'updateProfile']);
    //Request Support Student
    Route::post('/support-requests', [StudentController::class, 'storeSupportRequest']);
     //cập nhật profile
    // Route::post('/student/profile/{userID}', [StudentController::class, 'updateProfile']);
});
Route::get('/teacher/subjects', [TeacherController::class, 'getSubjectsWithTeachers']);

// Get all notifications for student
Route::delete('/student/notifications/{notificationID}', [StudentController::class, 'deleteNotification']);
Route::post('/student/notifications/{notificationID}/read', [StudentController::class, 'markAsRead']);
Route::get('/student/notifications/{receiverID}', [StudentController::class, 'getNotificationsByUser']);
/////
Route::middleware('auth:sanctum')->get('/teacher/classes', [TeacherController::class, 'getTeacherClasses']);
Route::get('/classes/{classId}/students', [TeacherController::class, 'getStudentsByClass']);
Route::get('/notifications/{receiverID}', [TeacherController::class, 'getNotificationsByUser']);
Route::get('/teacher/students/{id}', [TeacherController::class, 'showStudent']);
Route::post('/comments/send', [TeacherController::class, 'send']);
Route::get('/comments/history/{userId}/{classID}', [TeacherController::class, 'history']);
Route::put('/teacher/goals/{goal}/set-deadline', [TeacherController::class, 'updateGoalByTeacher']);
Route::get('/week-goals-progress', [TeacherController::class, 'getStudentProgress']);

Route::prefix('admin')->group(function () {
    Route::get('users', [AdminController::class, 'indexUsers']);
    Route::get('users/{id}', [AdminController::class, 'showUser']);
    Route::post('users', [AdminController::class, 'storeUser']);
    Route::put('users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('users/{id}', [AdminController::class, 'destroyUser']);

    Route::get('classes', [AdminController::class, 'indexClasses']);
    Route::get('classes/{id}', [AdminController::class, 'showClass']);
    Route::post('classes', [AdminController::class, 'storeClass']);
    Route::put('classes/{userID}', [AdminController::class, 'updateClass']);
    Route::delete('classes/{id}', [AdminController::class, 'destroyClass']);

    Route::get('classmate/{classID}', [AdminController::class, 'getClassWithStudents']);
    Route::get('students/unassigned', [AdminController::class, 'getUnassignedStudents']);
    Route::post('classes/{classID}/assign-student', [AdminController::class, 'assignStudentToClass']);

    Route::get('teachers', [AdminController::class, 'getClassesByTeacher']);
    Route::post('classes', [AdminController::class, 'createClassWithTeacher']);

    Route::get('stats', [AdminController::class, 'getStats']);
    Route::get('goals', [AdminController::class, 'getGoals']);
    Route::middleware('auth:api')->post('logout', [AdminController::class, 'logout']);

    Route::post('/notifications/{id}/read', [AdminController::class, 'markRead']);
    Route::delete('/notifications/{id}', [AdminController::class, 'destroy']);
    Route::post('/notifications/{id}/read', [AdminController::class, 'markAsRead']);

    Route::get('notifications', [AdminController::class, 'getNotifications']);

    // Route::get('report', [AdminController::class, 'report']);
    Route::get('reports', [AdminController::class, 'getStudentReport']);
    Route::get('getGoalsbyStudent/{userID}', [AdminController::class, 'getGoalsbyStudent']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/student/study-plans/semester/{semester}', [StudentController::class, 'getStudyPlansBySemester']);
    Route::get('/student/study-plans/{id}', [StudentController::class, 'getStudyPlan']);
    Route::post('/student/study-plans', [StudentController::class, 'createStudyPlan']);
    Route::put('/student/study-plans/{id}', [StudentController::class, 'updateStudyPlan']);
    Route::delete('/student/study-plans/{id}', [StudentController::class, 'deleteStudyPlan']);
    // Route::get('/student/profile', [StudentController::class, 'getProfile']);
    Route::get('/student/certificates', [StudentController::class, 'getCertificates']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Self Study Plan APIs
    Route::get('/student/self-study-plans/semester/{semester}', [StudentController::class, 'getSelfPlansBySemester']);
    Route::get('/student/self-study-plans/{id}', [StudentController::class, 'getSelfPlan']);
    Route::post('/student/self-study-plans', [StudentController::class, 'createSelfPlan']);
    Route::put('/student/self-study-plans/{id}', [StudentController::class, 'updateSelfStudyPlan']);
    Route::delete('/student/self-study-plans/{id}', [StudentController::class, 'updateSelfStudyPlan']);
    // Study Plans by Semester & Week
    Route::get('/student/study-plans/semester/{semester}/week/{week}', [StudentController::class, 'getStudyPlansBySemesterAndWeek']);

    // In-Class Plans by Semester & Week
    Route::get('/student/inclass-plans/semester/{semester}/week/{week}', [StudentController::class, 'getSelfPlansBySemesterAndWeek']);
    // In-Class Plans by Semester & Week
    Route::get('/student/self-study-plans/semester/{semester}/week/{week}', [StudentController::class, 'getSelfPlansBySemesterAndWeek']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/comments', [StudentController::class, 'storeComment']);
        Route::get('/comments', [StudentController::class, 'getComments']);
        Route::put('/comments/{id}/resolve', [StudentController::class, 'markAsResolved']);
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events', [StudentController::class, 'events']);
    Route::post('/events', [StudentController::class, 'storeEvent']);
    Route::put('/events/{id}', [StudentController::class, 'updateEvent']);
    Route::delete('/events/{id}', [StudentController::class, 'deleteEvent']);
});
