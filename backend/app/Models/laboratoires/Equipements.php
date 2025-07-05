<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Equipements extends Model
{
    protected $table = 'equipements';
    protected $primaryKey = 'code_equip';
    public $incrementing = true;
    protected $fillable = [
        'code_equip', 'nom_equip', 'ref_equip', 'desc_equip', 'etat', 'date_achat', 'valeur', 'localisation', 'code_lab'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
    public function entretiens()
    {
        return $this->hasMany(EntretienReparation::class, 'code_equip', 'code_equip');
    }
    public function reservations()
    {
        return $this->hasMany(ReservationAgent::class, 'code_equip', 'code_equip');
    }

    /**
     * Nettoie la description de l'équipement pour l'affichage sécurisé
     */
    public function getCleanDescAttribute()
    {
        return strip_tags($this->desc_equip);
    }

    /**
     * Limite la description de l'équipement pour l'affichage court
     */
    public function getShortDescAttribute()
    {
        return Str::limit(strip_tags($this->desc_equip), 100);
    }
}
