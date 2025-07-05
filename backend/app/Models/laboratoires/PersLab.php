<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class PersLab extends Model
{
    protected $table = 'pers_lab';
    protected $primaryKey = 'id_pers_lab';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_pers_lab', 'type_pers_lab', 'date_entree', 'date_sortie', 'statut'
    ];

    public function labos()
    {
        return $this->hasMany(Laboratoire::class, 'admin_pers_labo', 'id_pers_lab');
    }
    // Relation supprimée car les rôles sont maintenant gérés via laboratoire_pers_lab
    /*
    public function roles()
    {
        return $this->hasMany(PlRole::class, 'id_pers_lab', 'id_pers_lab');
    }
    */

    public function laboratoires()
    {
        return $this->belongsToMany(Laboratoire::class, 'laboratoire_pers_lab', 'id_pers_lab', 'code_lab')
                    ->withPivot('id_rl', 'date_affectation', 'date_fin_affectation', 'statut')
                    ->withTimestamps();
    }

    public function affectations()
    {
        return $this->hasMany(LaboratoirePersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
}
