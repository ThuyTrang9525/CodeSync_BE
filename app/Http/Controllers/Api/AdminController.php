<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;    
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassGroup; 
class AdminController extends Controller
{
    // Lấy thông tin thống kê cho admin
    public function getStats()
    {
        $teachers = User::where('role', 'teacher')->count(); // hoặc kiểm tra theo role_id
        $students = Student::count();
        $classes = ClassGroup::count();

        return response()->json([
            'teachers' => $teachers,
            'students' => $students,
            'classes' => $classes,
            ]);
    }
}