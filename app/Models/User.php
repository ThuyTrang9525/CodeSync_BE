<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Thêm trait này

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Thêm HasApiTokens vào đây

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
     * Get the attributes that should be cast.
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

    public function student()
    {
        return $this->hasOne(Student::class, 'usesID', 'id');
    }

    public function classGroups() {
        return $this->hasMany(ClassGroup::class, 'userID');
    }

    public function goal()
    {
        return $this->hasMany(Goal::class, 'usesID');
    }

    public function notify()
    {
        return $this->hasMany(Notification::class, 'usesID');
    }

}