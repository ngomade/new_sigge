<?php

namespace App\Models\requetes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_text',
        'request_id',
    ];

    public function request()
    {
        return $this->belongsTo(StudentRequest::class);
    }
}
