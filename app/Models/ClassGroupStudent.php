<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassGroupStudent extends Model
{
    protected $table = 'class_group_student';

    public function student()
    {
        return $this->belongsTo(Student::class, 'userID', 'classID');
    }
    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class, 'classID', 'classID');
    }
}
