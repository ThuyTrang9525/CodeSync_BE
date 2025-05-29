<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassMember extends Model
{
        protected $table = 'class_members';
        protected $primaryKey = 'id';
        public $incrementing = true;
        protected $fillable = [
            'classID',
            'userID',
            'role',
            'joined_at',
        ];
        
        public function classGroup()
        {
            return $this->belongsTo(ClassGroup::class, 'classID', 'classID');
        }
        public function user()
        {
            return $this->belongsTo(User::class, 'userID', 'userID');
        }
}
