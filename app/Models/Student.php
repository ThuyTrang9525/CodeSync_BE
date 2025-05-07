<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

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
        return $this->belongsTo(User::class, 'id');
    }
}
