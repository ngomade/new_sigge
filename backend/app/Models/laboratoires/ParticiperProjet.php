<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class ParticiperProjet extends Model
{
    protected $table = 'participer_projet';
    protected $primaryKey = 'id_participation';
    public $incrementing = true;
    protected $fillable = [
        'id_participation', 'code_projet', 'id_pers_lab', 'role', 'debut_participation', 'fin_participation'
    ];

    public function projet()
    {
        return $this->belongsTo(ProjetLabo::class, 'code_projet', 'code_projet');
    }
    public function membre()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
}
