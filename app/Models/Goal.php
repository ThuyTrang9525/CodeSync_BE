<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $table = 'goals';
    protected $primaryKey = 'goalID';
    public $timestamps = false; // Since your table doesn't have timestamps

    protected $fillable = [
        'title',
        'goalID',
        'studentID',
        'description',
        'semester',
        'deadline',
        'status',

    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentID', 'userID');
    }
}