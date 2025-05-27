<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notificationID'; // nếu không phải id

    public $timestamps = false; // nếu bạn dùng `createdAt` và không có `updated_at`

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