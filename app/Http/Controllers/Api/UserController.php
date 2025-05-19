<?php


namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\ClassGroup;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

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

        return response()->json($user, 201);  // Trả về người dùng vừa tạo
    }

    // Cập nhật thông tin người dùng
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
    } elseif ($user->role === 'TEACHER') {
        $extraData = Teacher::where('userID', $user->userID)->first();
    if ($extraData) {
        $classes = ClassGroup::where('userID', $extraData->userID)->get();
        }
    }

    return response()->json([
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'user'         => $user,
        'profile'      => $extraData,
        ...($user->role === 'TEACHER' ? ['classes' => $classes] : []),
    ]);

}


}
