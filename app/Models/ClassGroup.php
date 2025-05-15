<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
<<<<<<< HEAD
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
=======
    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'userID');
}
    protected $table = 'class_groups'; 

public function students()
{
    return $this->belongsToMany(Student::class, 'class_group_student', 'classID', 'userID');
}   
public function user() {
    return $this->belongsTo(User::class, 'userID');
}

}
>>>>>>> 1c75641a081b4496f21eb95bb0663daaf74add4b
