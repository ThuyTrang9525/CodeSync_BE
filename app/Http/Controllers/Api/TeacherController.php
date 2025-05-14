<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user(); // Lấy thông tin user từ token

        if ($user->role !== 'TEACHER') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $teacher = Teacher::where('userID', $user->userID)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $classes = ClassGroup::where('teacherID', $teacher->userID)->get();

        return response()->json([
            'classes' => $classes
        ]);
    }
}
