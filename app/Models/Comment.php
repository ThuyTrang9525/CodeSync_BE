<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'commentID';

    protected $fillable = [
        'senderID',
        'receiverID',
        'planID',
        'content',
        'planType',
        'isResolved',
        'classID',
        'createdAt',
        'updatedAt'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderID', 'userID');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiverID', 'userID');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'userID', 'userID');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'entryID', 'entryID');
    }
}