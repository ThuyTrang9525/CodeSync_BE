<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $table = 'support_requests';
    protected $primaryKey = 'requestID';
    public $timestamps = false;

    protected $fillable = [
        'senderID',
        'receiverID',
        'message',
        'status',
        'createdAt',
    ];
}