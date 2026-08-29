<?php

namespace App\Models;

use App\Models\concours\User;
use Illuminate\Database\Eloquent\Model;

class InfoExtra extends Model
{
    protected $table = 'info_extra';

    protected $primaryKey = 'code_info_extra';

    protected $fillable = [
        'nom_pere_user',
        'nom_mere_user',
        'telephone_tuteur_user',
        'email_tuteur_user',
        'telephone_mere',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'code_info_extra');
    }
}
