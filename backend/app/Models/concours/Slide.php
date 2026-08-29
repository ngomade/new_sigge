<?php

namespace App\Models\concours;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $table = 'slide';

    protected $fillable = [
        'first_title',
        'second_title',
        'photo',
        'code_pers',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'code_pers');
    }
}
