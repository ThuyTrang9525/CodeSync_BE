<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';
    protected $primaryKey = 'goalID';
    public $timestamps = false; // Since your table doesn't have timestamps

    protected $fillable = [
        'title',
        'goalID',
        'userID',
        'description',
        'semester',
        'deadline',
        'status',

    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'userID', 'userID');
    }
}

class ClassGroup extends Model
{
    protected $table = 'class_groups';
    protected $primaryKey = 'classID';

    // Quan hệ với giáo viên (dùng userID)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // Quan hệ với sinh viên (dùng userID)
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_group_student', 'classID', 'userID');
    }
}