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

    // Quan hệ ngược lên User (nếu cần)
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
    public function classGroups()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_student', 'studentID', 'classID');
    }

    public function goals()
    {
        return $this->hasMany(Goal::class, 'studentID', 'userID');
    }
}
