<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Student;

class UserController extends Controller
{
    // Lấy danh sách tất cả người dùng
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Lấy thông tin chi tiết của một người dùng
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Tạo mới một người dùng
    public function store(Request $request)
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

    // Cập nhật thông tin người dùng chung
    public function update(Request $request, $id)
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

    // Xóa một người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    // Đăng nhập và cấp token
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'role'     => 'required|in:STUDENT,TEACHER,ADMIN'
        ]);

        $user = User::where('email', $request->email)
                    ->where('role', $request->role)
                    ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid password'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $extraData = null;

        if ($user->role === 'STUDENT') {
            $extraData = Student::where('userID', $user->userID)->first();
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
            'profile'      => $extraData,
        ]);
    }

   // Cập nhật profile cho student
    public function updateProfile(Request $request, $userID)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required','email','max:255', Rule::unique('users','email')->ignore($userID,'userID')],
            'phoneNumber'  => 'nullable|string|max:20',
            'password'     => 'nullable|string|min:6',
        ]);

        $user = User::where('userID', $userID)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Cập nhật thông tin User
        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Cập nhật thông tin Student nếu có
        if ($user->student) {
            $student = $user->student;
            if (array_key_exists('phoneNumber', $validated)) {
                $student->phoneNumber = $validated['phoneNumber'];
            }
            $student->save();
        }

        // Trả về dữ liệu profile mới nhất
        $profile = $user->load('student');
        return response()->json($profile);
    }
}