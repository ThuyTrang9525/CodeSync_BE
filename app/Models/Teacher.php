<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
    public function classGroups()
{
    return $this->hasMany(ClassGroup::class, 'userID');
}

}
