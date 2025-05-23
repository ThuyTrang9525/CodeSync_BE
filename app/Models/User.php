<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userID';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Quan hệ 1-1 với Student (userID)
    public function student()
    {
        return $this->hasOne(Student::class, 'userID', 'userID');
    }
    public function teacher()
        {
            return $this->hasOne(Student::class, 'userID', 'userID');
        }
    // Quan hệ 1-n với ClassGroup (userID)
    public function classGroups()
    {
        return $this->hasMany(ClassGroup::class, 'userID', 'userID');
    }

    // Quan hệ 1-n với Goal (userID)
    public function goals()
    {
        return $this->hasMany(Goal::class, 'userID', 'userID');
    }

    // Quan hệ 1-n với Notification (userID)
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'userID', 'userID');
    }
}