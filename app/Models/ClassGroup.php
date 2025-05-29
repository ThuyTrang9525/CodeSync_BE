<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassGroup extends Model
{

    public function teacher()
    {
        return $this->belongsTo(User::class, 'userID');
    }


    protected $table = 'class_groups';
    protected $fillable = [
        'classID',
        'className',
        'userID',
    ];
    protected $primaryKey = 'classID';


    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_group_student', 'classID', 'userID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function classGroupStudents()
    {
        return $this->hasMany(ClassGroupStudent::class, 'classID', 'classID');
    }

    public function mainTeacherUser()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function studentss(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_group_student', 'classID', 'userID')
            ->wherePivot('role', 'student');
    }



    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_members', 'classID', 'userID')
            ->withPivot('role')
            ->withTimestamps();
    }
}
