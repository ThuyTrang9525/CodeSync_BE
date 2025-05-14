<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    protected $table = 'class_groups';
    protected $primaryKey = 'classID';

 public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacherID');
}

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_group_student', 'classID', 'studentID');
    }
}