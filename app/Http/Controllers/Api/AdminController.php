<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Goal;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;



class AdminController extends Controller
{
    // Quản lý thống kê
    public function getStats()
    {
        $teachers = User::where('role', 'teacher')->count();
        $students = Student::count();
        $classes = ClassGroup::count();

        return response()->json([
            'teachers' => $teachers,
            'students' => $students,
            'classes' => $classes,
        ]);
    }

    public function getGoals(){
        $goals = Goal::with('user')->get();
        return response()->json($goals);
    }

    public function getNotifications()
    {
        $notifications = Notification::with('user')->get();
        return response()->json($notifications);
    }

    public function markRead($id) {
    try {
        $noti = Notification::findOrFail($id);
        $noti->isRead = 1;
        $noti->save();

        return response()->json(['message' => 'Đã đánh dấu đã đọc']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function destroy($id) {
    $notification = Notification::findOrFail($id);
    $notification->delete();

    return response()->json(['message' => 'Thông báo đã được xoá']);
}


    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->isRead = $request->input('isRead', 1);
        $notification->save();

        return response()->json(['message' => 'Marked as read']);
    }

    
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function getStudentReport()
        {
            return User::where('role', 'STUDENT')
                    ->with('student')  
                    ->get();
        }

   
   public function getGoalsbyStudent($userID)
{
    $student = Student::with(['classGroups', 'user'])->findOrFail($userID);

    $classNames = $student->classGroups->pluck('className')->unique()->toArray();

    $classIDs = $student->classGroups->pluck('classID')->unique()->toArray();

    $goals = Goal::where('userID', $userID)
        ->whereIn('subject', $classNames)
        ->get();

    return response()->json([
        'userID' => $student->userID,
        'name' => $student->user->name ?? null,
        'email' => $student->user->email ?? null,
        'class_groups' => $student->classGroups,
        'goals' => $goals,
    ]);
}


    public function getClassesByTeacher()
    {
         $teachers = Teacher::with('user')->get();
        return response()->json($teachers);    
    }

    public function createClassWithTeacher(Request $request)
{
    $request->validate([
        'className' => 'required|string|max:255',
        'userID' => 'required|integer|exists:users,userID',  // Kiểm tra tồn tại userID trong users
    ]);

    try {
        // Tạo lớp mới
        $newClass = new ClassGroup();
        $newClass->className = $request->className;
        $newClass->userID = $request->userID; // Gán giáo viên ngay
        $newClass->save();

        return response()->json([
            'message' => 'Tạo lớp và gán giáo viên thành công',
            'class' => $newClass,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Server error',
            'error' => $e->getMessage(),
        ], 500);
    }
}





    //////////////////////////////////////////////////////////////////////// User
    public function indexUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

   public function storeUser(Request $request)
{
    // 1. Validate dữ liệu từ form
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:STUDENT,TEACHER,ADMIN',
    ]);

    // 2. Tạo user mới
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'],
    ]);

    // 3. Tạo bản ghi phụ theo role
    switch ($user->role) {
        case 'STUDENT':
            Student::create([
                'userID' => $user->userID,
                // thêm các trường khác nếu cần
            ]);
            break;

        case 'TEACHER':
            Teacher::create([
                'userID' => $user->userID,
                // thêm các trường khác nếu cần
            ]);
            break;

        // ADMIN không cần tạo gì thêm
    }

    // 4. Trả về response JSON
    return response()->json([
        'message' => 'User created successfully',
        'user' => $user
    ], 201);
}




    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'string',
            'email' => [
                'email',
                Rule::unique('users', 'email')->ignore($id, 'userID'),
            ],
            'password' => 'nullable|string|min:6',
            'role' => 'in:STUDENT,TEACHER,ADMIN',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }


    public function indexClasses()
    {
        return ClassGroup::with('user')->get();
    }

    public function showClass($id)
    {
        $classGroup = ClassGroup::with('user')->findOrFail($id);
        return response()->json($classGroup);
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'className' => 'required|string|max:255',
            'userID' => 'required|exists:users,id', 
        ]);

        $classGroup = ClassGroup::create([
            'className' => $request->className,
            'userID' => $request->userID,
        ]);

        return response()->json($classGroup, 201);
    }

   
    public function updateClass(Request $request, $classID)
{
    $request->validate([
        'className' => 'required|string|max:255',
        'userID' => 'nullable|exists:users,userID', 
    ]);

    $classGroup = ClassGroup::findOrFail($classID); // Sửa tên biến ở đây
    $classGroup->className = $request->input('className');
    $classGroup->userID = $request->input('userID');
    $classGroup->save();

    return response()->json($classGroup);
}



    public function destroyClass($id)
    {
        $classGroup = ClassGroup::findOrFail($id);
        $classGroup->delete();
        return response()->json(null, 204);
    }


    public function getClassWithStudents($classID)
{
    $class = ClassGroup::with([
        'members' => function ($query) {
            $query->wherePivot('role', 'student')->with('student');
        },
        'mainTeacherUser.teacher' // Load cả user và giáo viên của user
    ])->findOrFail($classID);

    return response()->json([
        'class' => [
            'id' => $class->classID,
            'name' => $class->className,
        ],
        'mainTeacher' => $class->mainTeacherUser ? [
            'userID' => $class->mainTeacherUser->userID,
            'name' => $class->mainTeacherUser->name,
            'email' => $class->mainTeacherUser->email,
            'teacher_info' => $class->mainTeacherUser->teacher ?? null
        ] : null,
        'students' => $class->members->map(function ($user) {
            return [
                'userID' => $user->userID,
                'name' => $user->name,
                'email' => $user->email,
                'student_info' => $user->student ?? null,
            ];
        }),
    ]);
}

    public function getUnassignedStudents()
{
    $students = Student::select('students.*', 'users.name','users.email') // lấy tất cả trường students + name từ users
        ->join('users', 'students.userID', '=', 'users.userID')
        ->whereNotIn('students.userID', function ($query) {
            $query->select('userID')->from('class_group_student');
        })
        ->whereNotIn('students.userID', function ($query) {
            $query->select('userID')->from('class_members');
        })
        ->get();

    return response()->json([
        'unassigned_students' => $students
    ]);
}



    public function assignStudentToClass(Request $request, $classID)
    {
        $request->validate([
            'userID' => 'required|exists:users,userID',
        ]);

        $class = ClassGroup::findOrFail($classID);
        $userID = $request->input('userID');

        // Kiểm tra xem user đã là thành viên chưa
        $alreadyMember = $class->members()
            ->where('users.userID', $userID)
            ->wherePivot('role', 'student')
            ->exists();

        if ($alreadyMember) {
            return response()->json([
                'message' => 'Student is already assigned to this class.'
            ], 409); 
        }

        $class->members()->attach($userID, ['role' => 'student']);

        return response()->json([
            'message' => 'Student assigned to class successfully.'
        ]);
    }





}   
