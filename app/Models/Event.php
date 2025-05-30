<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
       protected $table = 'events'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'eventID';

    // Nếu khoá chính kiểu số nguyên tự tăng
    public $incrementing = true;

    // Nếu kiểu khoá chính không phải int, có thể khai báo $keyType = 'string';
    protected $keyType = 'int';

    // Nếu bảng không có timestamp tự động created_at, updated_at
    public $timestamps = true;
    protected $fillable = [
        'userID', 'title', 'description', 'date', 'start_time', 'end_time', 'color',
    ];

    // Nếu muốn khai báo quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
