<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    public function teacher()
        {
            return $this->belongsTo(Teacher::class, 'userID');
        }
    protected $table = 'class_groups'; 

    public function students()
        {
            return $this->belongsToMany(Student::class, 'class_group_student', 'classID', 'userID');
        }   
    public function user() 
        {
            return $this->belongsTo(User::class, 'userID');
        }

}
