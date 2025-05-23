<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;
    protected $table = 'students';
    protected $primaryKey = 'userID';
    public $incrementing = false;
    protected $keyType = 'int';

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

    // Quan hệ với User
    public function user()
    {
        return $this->hasOne(User::class, 'userID', 'userID');
    }

    // Quan hệ với lớp học (dùng userID)
    public function classGroups()
    {
        return $this->belongsToMany(ClassGroup::class, 'class_group_student', 'userID', 'classID');
    }

    // Quan hệ với Goal (userID)
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
    public function classGroupStudents()
    {
        return $this->hasMany(ClassGroupStudent::class, 'userID', 'userID');
    }
}