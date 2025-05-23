<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'userID';

    protected $fillable = [
        'userID',
        'dateOfBirth',
        'gender',
        'address',
        'phoneNumber',
        'avatarURL',
        'enrollmentDate',
        'bio',
    ];

    // Quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // Quan hệ với lớp học (dùng userID)
    public function classGroups()
    {
        return $this->belongsToMany(ClassGroup::class, 'class_group_student', 'userID', 'classID');
    }


    public function goals()
    {
        return $this->hasMany(Goal::class, 'userID', 'userID');
    }
    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class, 'userID', 'userID');
    }

    // Quan hệ với SelfStudyPlan
    public function selfStudyPlans()
    {
        return $this->hasMany(SelfStudyPlan::class, 'userID', 'userID');
    }
}