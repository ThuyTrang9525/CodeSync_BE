<?php
// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'commentID';

    protected $fillable = [
        'entryID',
        'userID',
        'content',
        'createAt',
        'updateAt',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'userID', 'userID');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'entryID', 'entryID');
    }
}
