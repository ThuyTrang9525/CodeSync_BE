<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $timestamps = false;
    protected $table = 'teachers';
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

    public function user()
    {
        return $this->hasOne(User::class, 'userID','userID');
    }

    public function classGroups()
    {
        return $this->hasMany(ClassGroup::class, 'userID');
    }

}
