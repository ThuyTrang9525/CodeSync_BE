<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\ClassGroup;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\StudyPlan;
use App\Models\SelfStudyPlan;
use App\Http\Resources\GoalResource;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportRequest;


class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json(Student::all());
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
        $week = request()->query('week');  // Lấy tham số week từ query string

        if (!$week) {
            return response()->json(['message' => 'Week parameter is required'], 400);
        }

        $user = Auth::user();

        if ($user->role !== 'STUDENT') {
            return response()->json(['message' => 'Only students can access goals'], 403);
        }

        $goals = Goal::where('userID', $user->userID)
            ->where('semester', $semester)
            ->where('week', $week)
            ->orderBy('deadline')
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
            'subject' => 'sometimes|string',
            'week' => 'sometimes|integer',
        ]);

        $status = $request->has('status') ? $request->status : 'not-started';

        $goal = Goal::create([
            'title' => $validated['title'],
            'userID' => $user->userID,
            'description' => $validated['description'],
            'semester' => $validated['semester'],
            'deadline' => $validated['deadline'],
            'status' => $status,
            'subject' => $validated['subject'] ?? null,
            'week' => $validated['week'] ?? null,
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
            'title' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'semester' => 'sometimes|required|string',
            'deadline' => 'sometimes|required|date',
            'status' => 'sometimes|required|string',
            'subject' => 'sometimes|string',
            'week' => 'sometimes|integer',
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


    public function getSelfPlansBySemester($semester)
    {
        $userId = Auth::id();

        return response()->json(
            SelfStudyPlan::where('userID', $userId)
                ->where('semester', $semester)
                ->orderBy('date', 'desc')
                ->get()
        );
    }

    public function createSelfPlan(Request $request)
    {
        $data = $request->validate([
            'semester' => 'required|string',
            'date' => 'required|date',
            'week' => 'required|integer',
            'skill' => 'required|string',
            'lessonSummary' => 'nullable|string',
            'time_allocation' => 'nullable|integer',
            'concentration' => 'nullable|integer',
            'resources' => 'nullable|string',
            'activities' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['userID'] = Auth::id();

        return response()->json(SelfStudyPlan::create($data), 200);
    }


    // Tạo mới study plan

    public function createStudyPlan(Request $request)
    {
        try {
            $data = $request->validate([
                'type' => 'in:in_class',
                'semester' => 'required|string',
                'week' => 'required|integer',
                'date' => 'required|date',
                'skill' => 'required|string',
                'lessonSummary' => 'nullable|string',
                'selfAssessment' => 'nullable|integer',
                'difficulties' => 'nullable|string',
                'planToImprove' => 'nullable|string',
                'problemSolved' => 'nullable|boolean',
            ]);

            $data['userID'] = Auth::id();

            // Nếu là study plan in class thì dùng StudyPlan, còn self-study thì dùng SelfStudyPlan
            $plan = StudyPlan::create($data);

            if (!$plan) {
                return response()->json(['message' => 'Không thể lưu kế hoạch học tập'], 500);
            }

            return response()->json(['message' => 'Tạo thành công', 'data' => $plan], 201);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo study plan: ' . $e->getMessage());
            return response()->json([
                'message' => 'Đã xảy ra lỗi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getSelfPlansBySemesterAndWeek($semester, $week)
    {
        $userId = Auth::id();

        return response()->json(
            SelfStudyPlan::where('userID', $userId)
                ->where('semester', $semester)
                ->where('week', $week)
                ->orderBy('date', 'desc')
                ->get()
        );
    }
    public function getStudyPlansBySemesterAndWeek($semester, $week)
    {
        $userId = Auth::id();

        return response()->json(
            StudyPlan::where('userID', $userId)
                ->where('semester', $semester)
                ->where('week', $week)
                ->orderBy('date', 'desc')
                ->get()
        );
    }

    // Cập nhật study plan
    public function updateSelfStudyPlan(Request $request, $planID)
    {
        $plan = SelfStudyPlan::findOrFail($planID);

        if ($plan->userID !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'sometimes|in:SELF_STUDY',
            'semester' => 'sometimes|string',
            'date' => 'sometimes|date',
            'skill' => 'sometimes|string',
            'lessonSummary' => 'nullable|string',
            'concentration' => 'nullable|integer',
            'resources' => 'nullable|string',
            'activities' => 'nullable|string',
            'evaluation' => 'nullable|string',
            'notes' => 'nullable|string',
            'time_allocation' => 'nullable|integer',
        ]);

        $plan->update($validated);


        return response()->json($plan);
    }
    public function updateStudyPlan(Request $request, $planID)
    {
        $plan = StudyPlan::findOrFail($planID);

        if ($plan->userID !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([

            'semester' => 'required|string',
            'date' => 'required|date',
            'skill' => 'required|string',
            'lessonSummary' => 'nullable|string',
            'selfAssessment' => 'nullable|integer',
            'difficulties' => 'nullable|string',
            'planToImprove' => 'nullable|string',
            'problemSolved' => 'nullable|boolean',
        ]);

        $plan->update($validated);

        return response()->json($plan);
    }
    public function deleteStudyPlan( $planID)
    {
        $plan = StudyPlan::findOrFail($planID);

        if ($plan->userID !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $plan->delete();

        return response()->json(['message' => 'Study plan deleted successfully']);
    }
     
    public function deleteSelfStudyPlan($planID)
    {
        $plan = SelfStudyPlan::findOrFail($planID);

        if ($plan->userID !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $plan->delete();

        return response()->json(['message' => 'Self-study plan deleted successfully']);
    }

public function getStudentClasses()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'STUDENT') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $student = Student::where('userID', $user->userID)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        }

        $classes = $student->classGroups()->with('teacher')->get();

        return response()->json([
            'student' => $user->name ?? $user->email,
            'classes' => $classes
        ]);
    }

    //xóa thông báo và dánh dấu đã đọc

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


    public function getProfile(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Giả sử User model có quan hệ student() trả về model Student
    $student = $user->student;

    return response()->json([
        'name' => $user->name,
        'email' => $user->email,
        'student' => $student
    ]);
}

    public function getCertificates()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Giả sử User model có quan hệ certificates() trả về danh sách chứng chỉ
        $certificates = $user->certificates;

        return response()->json($certificates);
    }

    public function updateProfile(Request $request, $userID)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'dateOfBirth' => 'required|date',
        'gender' => 'required|string',
        'address' => 'required|string|max:255',
        'phoneNumber' => 'required|string|max:20',
        'avatarURL' => 'nullable|url',
        'enrollmentDate' => 'required|date',
        'bio' => 'nullable|string',
    ]);

    $user = User::where('userID', $userID)->first();
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Cập nhật bảng users
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->save();

    // Cập nhật bảng students
    $student = $user->student;
    if ($student) {
        $student->dateOfBirth = $validated['dateOfBirth'];
        $student->gender = $validated['gender'];
        $student->address = $validated['address'];
        $student->phoneNumber = $validated['phoneNumber'];
        $student->avatarURL = $validated['avatarURL'] ?? null;
        $student->enrollmentDate = $validated['enrollmentDate'];
        $student->bio = $validated['bio'] ?? null;
        $student->save();
    }

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }



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
            ->groupBy(
                'notifications.notificationID',
                'notifications.content',
                'notifications.createdAt',
                'notifications.isRead',
                'class_groups.className',
                'users.name'
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

    public function storeComment(Request $request)
    {
        $request->validate([
            'planID' => 'required|integer',
            'planType' => 'required|string|in:in_class,study',
            'senderID' => 'required|integer',
            'content' => 'required|string'
        ]);

        $senderID = $request->senderID;
        $receiverID = null;

        // Tìm người bị tag thông qua email (dạng @email@example.com)
        preg_match('/@([^\s]+)/', $request->content, $matches);

        if ($matches) {
            $email = $matches[1];

            $user = DB::table('users')
                ->where('email', $email)
                ->select('userID')
                ->first();

            if ($user) {
                $receiverID = $user->userID;
            }
        }

        // Tạo comment mới
        $commentID = DB::table('comments')->insertGetId([
            'planID' => $request->planID,
            'planType' => $request->planType,
            'senderID' => $senderID,
            'receiverID' => $receiverID,
            'content' => $request->content,
            'createdAt' => now(),
            'updatedAt' => now()
        ]);

        // Nếu có người được tag => thêm notification
        if ($receiverID) {
            DB::table('notifications')->insert([
                'receiverID' => $receiverID,
                'content' => 'Bạn được tag trong một bình luận: "' . $request->content . '"',
                'type' => 'INTERACTION',
                'isRead' => 0,
                'createdAt' => now(),
                'senderID' => $senderID,
                'classID' => null // nếu có classID thì thay thế ở đây
            ]);
        }

        return response()->json([
            'commentID' => $commentID,
            'planID' => $request->planID,
            'planType' => $request->planType,
            'senderID' => $senderID,
            'receiverID' => $receiverID,
            'content' => $request->content,
            'createdAt' => now(),
            'updatedAt' => now()
        ], 201);
    }

    // Lấy tất cả bình luận theo entryID
    public function getComments(Request $request)
    {
        $request->validate([
            'planID' => 'required|integer',
            'planType' => 'required|string|in:in_class,study',
        ]);

        $planID = $request->planID;
        $planType = $request->planType;

        $comments = DB::table('comments')
            ->where('planID', $planID)
            ->where('planType', $planType)
            ->where('isResolved', false)
            ->orderBy('createdAt', 'desc')
            ->get();

        return response()->json($comments);
    }

    public function markAsResolved($commentID)
    {
        $updated = DB::table('comments')
            ->where('commentID', $commentID)
            ->update(['isResolved' => true]);

        if ($updated) {
            return response()->json(['message' => 'Comment marked as resolved']);
        }

        return response()->json(['error' => 'Comment not found'], 404);
    }
    public function showProfile($userID)
    {
        $user = User::find($userID);
        if (!$user) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
    public function events()
    {
        return Event::where('userID', Auth::id())->get();
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'color' => 'nullable|string|max:20',
        ]);

        return Event::create([
            'userID' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'color' => $validated['color'] ?? '#000000',
        ]);
    }

    public function updateEvent(Request $request, $eventID)
    {
        $event = Event::findOrFail($eventID);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after_or_equal:start_time',
            'color' => 'sometimes|nullable|string|max:20',
        ]);

        $event->update($validated);
        return $event;
    }

    public function deleteEvent($eventID)
    {
        Event::findOrFail($eventID)->delete();
        return response()->json(['message' => 'Deleted']);
    }


    // Student Request Support

    public function storeSupportRequest(Request $request)
    {
        $request->validate([
            'recipientId' => 'nullable|exists:users,userID',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $user = Auth::user();

        $supportRequest = SupportRequest::create([
            'senderID' => $user->userID,
            'receiverID' => $request->recipientId,
            'message' => "[{$request->category}] {$request->title}\n{$request->description}",
            'status' => 'pending',
            'createdAt' => now(),
        ]);

        return response()->json(['message' => 'Yêu cầu đã được gửi thành công!', 'data' => $supportRequest], 201);
    }
}
