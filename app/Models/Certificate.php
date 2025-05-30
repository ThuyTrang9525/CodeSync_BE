<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = false;
    protected $table = 'certificates';
    protected $primaryKey = 'certificateID';
    protected $fillable = [
        'certificateID',
        'title',
        'issuer',
        'issueDate',
        'exporationDate',
        'fileURL',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
