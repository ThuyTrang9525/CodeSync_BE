<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'notificationID'; // nếu không phải id

    protected $fillable = [
        'receiverID', 'content', 'type', 'isRead', 'createdAt',
        'senderID', 'classID'
    ];

    public $timestamps = false; // nếu bạn dùng `createdAt` và không có `updated_at`

    public function user() {
        return $this->belongsTo(User::class, 'receiverID', 'userID');
    }
}
