<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacherID', 'userID');
}
    protected $table = 'class_groups'; 

public function students()
{
    return $this->belongsToMany(Student::class, 'class_group_student', 'classID', 'studentID');
}   
}
