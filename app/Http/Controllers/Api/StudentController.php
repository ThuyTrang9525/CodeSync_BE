<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\StudyPlan;
use App\Http\Resources\GoalResource;
use Illuminate\Support\Facades\Auth;


class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json(Student::all());
    }

    public function getClasses()
    {
        return response()->json(ClassGroup::all());
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
}
