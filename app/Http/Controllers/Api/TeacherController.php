<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Goal;
use App\Models\User;
use App\Models\Comment;
use App\Models\ClassGroup;
use App\Models\Notification;
use Carbon\Carbon;

class TeacherController extends Controller
{
    // Lấy danh sách tất cả giáo viên
    public function index()
    {
        return Teacher::all();
    }

    // Thêm giáo viên mới
    public function store(Request $request)
    {
        $request->validate([
            'userID' => 'required|integer|unique:teachers,userID',
        ]);

        $teacher = Teacher::create($request->all());
        return response()->json($teacher, 201);
    }

    // Lấy thông tin 1 giáo viên
    public function show($userID)
    {
        return Teacher::findOrFail($userID);
    }

    // Cập nhật thông tin giáo viên
    public function update(Request $request, $userID)
    {
        $teacher = Teacher::findOrFail($userID);
        $teacher->update($request->all());
        return response()->json($teacher, 200);
    }

    // Xoá giáo viên
    public function destroy($userID)
    {
        Teacher::destroy($userID);
        return response()->json(null, 204);
    }
    //
    public function getTeacherClasses(Request $request)
        {
            $user = Auth::user();

            if ($user->role !== 'TEACHER') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $teacher = Teacher::where('userID', $user->userID)->first();

            if (!$teacher) {
                return response()->json(['message' => 'Teacher not found'], 404);
            }

            // Lấy danh sách lớp học và load students để đếm
            $classes = ClassGroup::where('userID', $teacher->userID)
                ->withCount('students') // Đếm students của mỗi class
                ->get();

            // Tính tổng số học sinh
            $totalStudents = $classes->sum('students_count');

            return response()->json([
                'classes' => $classes,
                'totalStudents' => $totalStudents
            ]);
        }

    //
    public function getStudentsByClass($classID)
    {
        $class = ClassGroup::with(['students.user'])->find($classID);

        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        $students = $class->students->map(function ($student) {
            return $student->user ?? null;
        })->filter()->values();

        return response()->json([
            // 'classID' => $class->classID,
            'students' => $students
        ]);
    }
    //
    public function getNotificationsByUser($receiverID)
    {
        try {
            $notifications = DB::table('notifications')
                ->where('notifications.receiverID', $receiverID)
                ->join('users', 'notifications.senderID', '=', 'users.userID')
                ->leftJoin('class_group_student', 'notifications.receiverID', '=', 'class_group_student.userID') // lấy thông tin lớp của người nhận
                ->leftJoin('class_groups', 'notifications.classID', '=', 'class_groups.classID')
                ->select(
                    'notifications.notificationID',
                    'notifications.content',
                    'notifications.createdAt',
                    'users.name as name',
                    'class_groups.className'
                )
                ->orderBy('notifications.createdAt', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function showStudent($id)
    {
        $student = Student::with(['user', 'goals', 'studyPlans', 'selfStudyPlans','event'])->find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json([
            'id' => $student->userID,
            'profile' => $student->user,
            'goals' => $student->goals,
            'study_plans' => $student->studyPlans,
            'self_study_plans' => $student->selfStudyPlans,
            'event' => $student->event
        ]);
    }
    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiverID' => 'required|exists:users,userID',
            'classID' => 'required|exists:class_groups,classID',
            'content' => 'required|string',
            'planID' => 'nullable|integer',
            'planType' => 'nullable|string'
        ]);

        $comment = Comment::create([
            'senderID' => Auth::id(),
            'receiverID' => $validated['receiverID'],
            'classID' => $validated['classID'],
            'content' => $validated['content'],
            'planID' => $validated['planID'] ?? 0,
            'planType' => $validated['planType'] ?? 0,
            'isResolved' => false,
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

    return response()->json($comment, 201);
    }

    public function history($userId, $classID)
   {
        $authId = Auth::id();

        $messages = Comment::where('classID', $classID)
            ->where(function ($query) use ($authId, $userId) {
                $query->where(function ($q) use ($authId, $userId) {
                    $q->where('senderID', $authId)
                    ->where('receiverID', $userId);
                })->orWhere(function ($q) use ($authId, $userId) {
                    $q->where('senderID', $userId)
                    ->where('receiverID', $authId);
                });
            })
            ->orderBy('createdAt')
            ->get();

        return response()->json($messages);
    }

    public function updateGoalByTeacher(Request $request, Goal $goal)
    {
        
        $validated = $request->validate([
            'deadline' => 'required|date',
            'senderID' => 'required|integer',
        ]);

        $originalDeadline = $goal->deadline;

        $goal->update([
            'deadline' => $validated['deadline'],
        ]);

        if ($originalDeadline !== $validated['deadline']) {
            Notification::create([
                'receiverID' => $goal->userID, 
                'senderID' => $validated['senderID'],  
                'content' => "The deadline for your goal '{$goal->title}' has been updated to " . Carbon::parse($validated['deadline'])->format('d/m/Y') . ".",
                // 'type' => 'Updated',
                'isRead' => false,
                'createdAt' => now(),
                'classID' => $validated['classID'] ?? null,
            ]);
        }

        return response()->json([
            'message' => 'Goal deadline updated and notification sent.',
            'goal' => $goal,
        ]);
    }
    public function getStudentProgress(Request $request)
    {
        $email = $request->query('email');
        $week = $request->query('week');
        $semester = $request->query('semester');

        if (!$email || !$week || !$semester) {
            return response()->json(['error' => 'Missing email or week'], 400);
        }

        $goals = DB::table('goals')
            ->join('users', 'goals.userID', '=', 'users.userID')
            ->where('users.email', $email)
            ->where('goals.week', $week)
            ->where('goals.semester', $semester)
            ->select('goals.status')
            ->get();

        $total = $goals->count();
        $completed = $goals->where('status', 'completed')->count(); 
        $progress = $total > 0 ? round(($completed / $total) * 100) : 0;

        return response()->json([
            'email' => $email,
            'week' => (int) $week,
            'semester' => $semester,
            'completed' => $completed,
            'total' => $total,
            'progress' => $progress,
        ]);
    }
    public function getSubjectsWithTeachers()
    {
        $classes = ClassGroup::with('teacher')
            ->get()
            ->map(function ($class) {
                return [
                    'classID' => $class->classID,
                    'className' => $class->className,
                    'teacherName' => $class->teacher ? $class->teacher->name : null,
                    'teacherID' => $class->userID,
                ];
            });

        return response()->json($classes);
    }
}
