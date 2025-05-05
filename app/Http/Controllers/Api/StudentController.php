<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

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
        if (!$s) return response()->json(['message'=>'Not found'],404);
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
        if (!$s) return response()->json(['message'=>'Not found'],404);

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
        if (!$s) return response()->json(['message'=>'Not found'],404);
        $s->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
