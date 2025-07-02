<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class ParticiperProjet extends Model
{
    protected $table = 'participer_projet';
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = null;
    protected $fillable = [
        'code_projet', 'id_pers_lab', 'id_user_ext', 'role', 'debut_participation', 'fin_participation'
    ];

    public function projet()
    {
        return $this->belongsTo(ProjetLabo::class, 'code_projet', 'code_projet');
    }
    public function membre()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
    public function userExterne()
    {
        return $this->belongsTo(UserExterne::class, 'id_user_ext', 'id_user_ext');
    }
}
