<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboChat extends Model
{
    use HasFactory;

    protected $table = 'labo_chats';

    protected $fillable = [
        'code_lab',
        'id_expediteur',
        'type_expediteur',
        'message',
    ];

    // Relations pour récupérer l'expéditeur (personnel, user, externe)
    public function expediteur()
    {
        if ($this->type_expediteur === 'personnel') {
            return $this->belongsTo(\App\Models\Personnel::class, 'id_expediteur', 'code_pers');
        } elseif ($this->type_expediteur === 'user') {
            return $this->belongsTo(\App\Models\Users::class, 'id_expediteur', 'code_user');
        } elseif ($this->type_expediteur === 'externe') {
            return $this->belongsTo(\App\Models\laboratoires\UserExterne::class, 'id_expediteur', 'id_user_ext');
        }

        return null;
    }
}
