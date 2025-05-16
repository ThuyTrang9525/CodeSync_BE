<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Goal;
use App\Models\Notification;

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
        $noti = Notification::findOrFail($id);
        $noti->isRead = 1;
        $noti->save();
        return response()->json(['message' => 'Đã đánh dấu đã đọc']);
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

    public function destroy($id) {
        Notification::destroy($id);
        return response()->json(['message' => 'Đã xóa']);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:STUDENT,TEACHER,ADMIN',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
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

    //////////////////////////////////////////////////////////////////////// class
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
            'userID' => 'required|exists:users,userID',
        ]);

        $classGroup = ClassGroup::create($request->all());
        return response()->json($classGroup, 201);
    }

    public function updateClass(Request $request, $id)
    {
        $classGroup = ClassGroup::findOrFail($id);
        $classGroup->update($request->all());
        return response()->json($classGroup);
    }

    public function destroyClass($id)
    {
        $classGroup = ClassGroup::findOrFail($id);
        $classGroup->delete();
        return response()->json(null, 204);
    }
}
