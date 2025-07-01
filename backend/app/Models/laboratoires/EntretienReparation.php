<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class EntretienReparation extends Model
{
    protected $table = 'entretien_reparation';
    protected $primaryKey = 'id_entretien';
    public $incrementing = true;
    protected $fillable = [
        'id_entretien', 'code_equip', 'id_pers_lab', 'debut_entretien', 'fin_entretien', 'type_entretien', 'desc_entretien', 'cout'
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipements::class, 'code_equip', 'code_equip');
    }
    public function membre()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
}
