<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        
        'receiverID',
        'senderID',
        'content',
        'type',
        'isRead',
        'createdAt',
        'classID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'receiverID', 'userID');
    }
}
