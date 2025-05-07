<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function getStats()
    {
        $teachers = User::where('role', 'teacher')->count(); // hoặc kiểm tra theo role_id
        $students = Student::count();

        return response()->json([
            'teachers' => $teachers,
            'students' => $students,
        ]);
    }
}
