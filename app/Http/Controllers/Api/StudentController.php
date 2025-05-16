<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\StudyPlan;
use App\Models\InClassStudyPlan;
use App\Http\Resources\GoalResource;
use Illuminate\Support\Facades\Auth;


class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json(Student::all());
    }

   public function getStudentClasses()
{
    $user = Auth::user();

    // Kiểm tra quyền truy cập
    if (!$user || $user->role !== 'STUDENT') {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized'
        ], 403);
    }

    // Lấy thông tin sinh viên dựa trên userID
    $student = Student::where('userID', $user->userID)->first();

    if (!$student) {
        return response()->json([
            'status' => 'error',
            'message' => 'Student not found'
        ], 404);
    }

    // Lấy danh sách lớp học có kèm thông tin giáo viên
    $classes = $student->classGroups()->with(['teacher'])->get();

    return response()->json([
        'student' => $user->name ?? $user->email,
        'classes' => $classes
    ]);
}


    // GET /api/students/{id}
    public function show($id)
    {
        $s = Student::find($id);
        if (!$s) return response()->json(['message' => 'Not found'], 404);
        return response()->json($s);
    }

    // POST /api/students
    public function store(Request $req)
    {
        $data = $req->validate([
            'userID'         => 'required|exists:users,id',
            'dateOfBirth'    => 'required|date',
            'gender'         => 'required|in:Male,Female,Other',
            'address'        => 'required|string',
            'phoneNumber'    => 'required|string',
            'avatarURL'      => 'nullable|url',
            'enrollmentDate' => 'required|date',
            'bio'            => 'nullable|string',
        ]);

        $student = Student::create($data);
        return response()->json($student, 201);
    }

    // PUT /api/students/{id}
    public function update(Request $req, $id)
    {
        $s = Student::find($id);
        if (!$s) return response()->json(['message' => 'Not found'], 404);

        $data = $req->validate([
            'userID'         => 'sometimes|exists:users,id',
            'dateOfBirth'    => 'sometimes|date',
            'gender'         => 'sometimes|in:Male,Female,Other',
            'address'        => 'sometimes|string',
            'phoneNumber'    => 'sometimes|string',
            'avatarURL'      => 'nullable|url',
            'enrollmentDate' => 'sometimes|date',
            'bio'            => 'nullable|string',
        ]);

        $s->update($data);
        return response()->json($s);
    }

    // DELETE /api/students/{id}
    public function destroy($id)
    {
        $s = Student::find($id);
        if (!$s) return response()->json(['message' => 'Not found'], 404);
        $s->delete();
        return response()->json(['message' => 'Deleted']);
    }


    public function getGoals(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a student
        if ($user->role !== 'STUDENT') {
            return response()->json(['message' => 'Only students can access goals'], 403);
        }

        // Get the student record
        $student = Student::where('userID', $user->userID)->first();

        if (!$student) {
            return response()->json(['message' => 'Student record not found'], 404);
        }

        // Query goals for the student
        $query = Goal::where('userID', $student->userID);

        // Optional semester filter
        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        // Optional status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Get the goals
        $goals = $query->get();

        return GoalResource::collection($goals);
    }

    /**
     * Display a list of all semesters with goals.
     */
    public function getSemesters()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a student
        if ($user->role !== 'STUDENT') {
            return response()->json(['message' => 'Only students can access goals'], 403);
        }

        // Get distinct semesters for the student
        $semesters = Goal::where('userID', $user->userID)
            ->select('semester')
            ->distinct()
            ->orderBy('semester')
            ->pluck('semester');

        return response()->json(['semesters' => $semesters]);
    }

    /**
     * Display goals for a specific semester.
     */
    public function getSemesterGoals($semester)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is a student
        if ($user->role !== 'STUDENT') {
            return response()->json(['message' => 'Only students can access goals'], 403);
        }

        // Get goals for the specified semester
        $goals = Goal::where('userID', $user->userID)
            ->where('semester', $semester)
            ->get();

        return GoalResource::collection($goals);
    }

    /**
     * Store a newly created goal.
     */
    public function storeGoal(Request $request)
    {
        $user = Auth::user();  // Get the authenticated user
        if ($user->role !== 'STUDENT') {
            return response()->json(['message' => 'Only students can create goals'], 403);
        }

        // Gán validate vào biến $validated
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'semester' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $status = $request->has('status') ? $request->status : 'not-started';

        $goal = Goal::create([
            'title' => $validated['title'],
            'userID' => $user->userID,
            'description' => $validated['description'],
            'semester' => $validated['semester'],
            'deadline' => $validated['deadline'],
            'status' => $status,
        ]);
        return new GoalResource($goal);
    }



    /**
     * Display the specified goal.
     */
    public function showGoal(Goal $goal)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the goal belongs to the authenticated student
        if ($user->role !== 'STUDENT' || $goal->userID !== $user->userID) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new GoalResource($goal);
    }

    /**
     * Update the specified goal.
     */
    public function updateGoal(Request $request, Goal $goal)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the goal belongs to the authenticated student
        if ($user->role !== 'STUDENT' || $goal->userID !== $user->userID) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'description' => 'sometimes|required|string',
            'semester' => 'sometimes|required|string',
            'deadline' => 'sometimes|required|date',
            'status' => 'sometimes|required|string',
        ]);

        $goal->update($validated);

        return new GoalResource($goal);
    }

    /**
     * Remove the specified goal.
     */
    public function destroyGoal(Goal $goal)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the goal belongs to the authenticated student
        if ($user->role !== 'STUDENT' || $goal->userID !== $user->userID) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $goal->delete();

        return response()->json(['message' => 'Goal deleted successfully']);
    }



    public function getStudyPlansBySemester($semester)
    {
        $userId = Auth::id();

        return response()->json(
            StudyPlan::where('userID', $userId)
                ->where('semester', $semester)
                ->orderBy('date', 'desc')
                ->get()
        );
    }


    public function getInClassPlansBySemester($semester)
    {
        $userId = Auth::id();

        return response()->json(
            InClassStudyPlan::where('userID', $userId)
                ->where('semester', $semester)
                ->orderBy('date', 'desc')
                ->get()
        );
    }

    public function createInClassPlan(Request $request)
    {
        $data = $request->validate([
            'semester' => 'required|string',
            'date' => 'required|date',
            'skill' => 'required|string',
            'lessonSummary' => 'nullable|string',
            'selfAssessment' => 'nullable|integer',
            'difficulties' => 'nullable|string',
            'planToImprove' => 'nullable|string',
            'problemSolved' => 'nullable|boolean',
        ]);

        $data['userID'] = Auth::id();

        return response()->json(InClassStudyPlan::create($data), 201);
    }


    // Tạo mới study plan
    public function createStudyPlan(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:SELF_STUDY,IN_CLASS',
                'semester' => 'required|string',
                'date' => 'required|date',
                'skills' => 'required|string',
                'lessonSummary' => 'nullable|string',
                'selfAssessment' => 'nullable|integer',
                'difficulties' => 'nullable|string',
                'planToImprove' => 'nullable|string',
                'problemSolved' => 'nullable|boolean',
                'concentration' => 'nullable|integer',
                'resources' => 'nullable|string',
                'activities' => 'nullable|string',
                'evaluation' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $validated['userID'] = Auth::id();

            $plan = StudyPlan::create($validated);

            if (!$plan) {
                return response()->json(['message' => 'Không thể lưu kế hoạch học tập'], 500);
            }

            return response()->json(['message' => 'Tạo thành công', 'data' => $plan], 201);
        } catch (\Exception $e) {
            // Ghi log nếu muốn
            Log::error('Lỗi khi tạo study plan: ' . $e->getMessage());

            // Trả lại lỗi cụ thể cho Postman
            return response()->json([
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Cập nhật study plan
    public function updateStudyPlan(Request $request, $id)
    {
        $plan = StudyPlan::findOrFail($id);

        if ($plan->userID !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'in:SELF_STUDY,IN_CLASS',
            'semester' => 'string',
            'date' => 'date',
            'skill' => 'string',
            'lessonSummary' => 'nullable|string',
            'selfAssessment' => 'nullable|integer',
            'difficulties' => 'nullable|string',
            'planToImprove' => 'nullable|string',
            'problemSolved' => 'nullable|boolean',
            'concentration' => 'nullable|integer',
            'resources' => 'nullable|string',
            'activities' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $plan->update($validated);

        return response()->json($plan);
    }

    // Xóa study plan
    public function deleteStudyPlan($id)
    {
        $plan = StudyPlan::findOrFail($id);

        if ($plan->userID !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $plan->delete();

        return response()->json(['message' => 'Study plan deleted']);
    }


    // Lấy danh sách thông báo của người dùng
   public function getNotificationsByUser($receiverID)
{
    try {
        $notifications = DB::table('notifications')
            ->where('notifications.receiverID', $receiverID)
            ->leftJoin('users', 'notifications.senderID', '=', 'users.userID') 
            ->leftJoin('class_groups', 'notifications.classID', '=', 'class_groups.classID')
            ->select(
                'notifications.notificationID',
                'notifications.content',
                'notifications.createdAt',
                'notifications.isRead',
                'class_groups.className',
                'users.name as senderName' 
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

    //xóa thông báo và dánh dấu đã đọc
    public function deleteNotification($notificationID)
    {
        try {
            DB::table('notifications')->where('notificationID', $notificationID)->delete();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function markAsRead($notificationID)
    {
        try {
            DB::table('notifications')
                ->where('notificationID', $notificationID)
                ->update(['isRead' => 1]);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
