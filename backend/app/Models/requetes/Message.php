<?php

namespace App\Models\requetes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'sender_id',
        'message'
    ];

    public function request()
    {
        return $this->belongsTo(StudentRequest::class);
    }

    // public function sender()
    // {
    //     return $this->belongsTo(User::class, 'sender_id');
    // }
}
