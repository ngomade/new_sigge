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

    // Relations avec Personnel et Users selon le type
    public function personnel()
    {
        return $this->belongsTo(\App\Models\Personnel::class, 'id_pers_lab', 'code_pers');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Users::class, 'id_pers_lab', 'code_user');
    }

    // Méthode pour récupérer le nom complet selon le type
    public function getNomCompletAttribute()
    {
        if ($this->type_pers_lab === 'personnel' && $this->personnel) {
            return $this->personnel->nom_pers . ' ' . $this->personnel->prenom_pers;
        } elseif ($this->type_pers_lab === 'user' && $this->user) {
            return $this->user->nom_user . ' ' . $this->user->prenom_user;
        } elseif ($this->type_pers_lab === 'user_externe') {
            // Pour les utilisateurs externes, on peut récupérer depuis UserExterne
            $userExterne = \App\Models\laboratoires\UserExterne::where('id_user_ext', $this->id_pers_lab)->first();
            return $userExterne ? $userExterne->nom_user_ext . ' ' . $userExterne->prenom_user_ext : 'N/A';
        }
        return 'N/A';
    }
}
