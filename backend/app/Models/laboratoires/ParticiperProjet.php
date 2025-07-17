<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class ParticiperProjet extends Model
{
    protected $table = 'participer_projet';
    public $incrementing = true; // ID auto-incrémenté
    public $timestamps = true;
    protected $primaryKey = 'id'; // Clé primaire est 'id'
    protected $fillable = [
        'code_projet', 'id_pers_lab', 'id_user_ext', 'role', 'debut_participation', 'fin_participation'
    ];

    protected $casts = [
        'debut_participation' => 'date',
        'fin_participation' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot method pour ajouter des validations
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($participerProjet) {
            // Vérifier qu'au moins un participant est défini
            if (empty($participerProjet->id_pers_lab) && empty($participerProjet->id_user_ext)) {
                throw new \Exception('Au moins un participant (interne ou externe) doit être défini.');
            }

            // Vérifier qu'un seul type de participant est défini
            if (!empty($participerProjet->id_pers_lab) && !empty($participerProjet->id_user_ext)) {
                throw new \Exception('Un participant ne peut pas être à la fois interne et externe.');
            }
        });
    }

    public function projet()
    {
        return $this->belongsTo(ProjetLabo::class, 'code_projet', 'code_projet');
    }

    public function membre()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
    public function persLab()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }

    public function userExterne()
    {
        return $this->belongsTo(UserExterne::class, 'id_user_ext', 'id_user_ext');
    }

    /**
     * Obtenir le participant (interne ou externe)
     */
    public function getParticipantAttribute()
    {
        if ($this->id_pers_lab) {
            return $this->membre;
        } elseif ($this->id_user_ext) {
            return $this->userExterne;
        }
        return null;
    }

    /**
     * Obtenir le nom du participant
     */
    public function getNomParticipantAttribute()
    {
        if ($this->id_pers_lab && $this->membre) {
            return $this->membre->nom_complet;
        } elseif ($this->id_user_ext && $this->userExterne) {
            return $this->userExterne->nom_user_ext . ' ' . $this->userExterne->prenom_user_ext;
        }
        return 'Participant inconnu';
    }

    /**
     * Obtenir le type de participant
     */
    public function getTypeParticipantAttribute()
    {
        if ($this->id_pers_lab) {
            return 'interne';
        } elseif ($this->id_user_ext) {
            return 'externe';
        }
        return 'inconnu';
    }

    /**
     * Vérifier si la participation est active
     */
    public function getEstActiveAttribute()
    {
        $now = now();
        return $this->debut_participation <= $now &&
               (!$this->fin_participation || $this->fin_participation >= $now);
    }

    /**
     * Scopes pour filtrer les participants
     */
    public function scopeInternes($query)
    {
        return $query->whereNotNull('id_pers_lab');
    }

    public function scopeExternes($query)
    {
        return $query->whereNotNull('id_user_ext');
    }

    public function scopeActifs($query)
    {
        $now = now();
        return $query->where('debut_participation', '<=', $now)
                    ->where(function($q) use ($now) {
                        $q->whereNull('fin_participation')
                          ->orWhere('fin_participation', '>=', $now);
                    });
    }

    public function scopeByProjet($query, $code_projet)
    {
        return $query->where('code_projet', $code_projet);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
