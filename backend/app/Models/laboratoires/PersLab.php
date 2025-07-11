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
    
    protected $casts = [
        'date_entree' => 'date',
        'date_sortie' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function labos()
    {
        return $this->hasMany(Laboratoire::class, 'admin_pers_labo', 'id_pers_lab');
    }

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
    
    // Relations pour les réservations et entretiens via LaboratoirePersLab
    public function reservations()
    {
        return $this->hasManyThrough(
            ReservationAgent::class,
            LaboratoirePersLab::class,
            'id_pers_lab', // Foreign key on laboratoire_pers_lab table
            'id_pers_lab', // Foreign key on reservation_agent table
            'id_pers_lab', // Local key on pers_lab table
            'id' // Local key on laboratoire_pers_lab table
        );
    }
    
    public function entretiens()
    {
        return $this->hasManyThrough(
            EntretienReparation::class,
            LaboratoirePersLab::class,
            'id_pers_lab', // Foreign key on laboratoire_pers_lab table
            'id_pers_lab', // Foreign key on entretien_reparation table
            'id_pers_lab', // Local key on pers_lab table
            'id' // Local key on laboratoire_pers_lab table
        );
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
    public function getNomCompletAttribute(): string
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
    
    // Accesseurs supplémentaires
    public function getNomAttribute(): string
    {
        if ($this->type_pers_lab === 'personnel' && $this->personnel) {
            return $this->personnel->nom_pers;
        } elseif ($this->type_pers_lab === 'user' && $this->user) {
            return $this->user->nom_user;
        } elseif ($this->type_pers_lab === 'user_externe') {
            $userExterne = \App\Models\laboratoires\UserExterne::where('id_user_ext', $this->id_pers_lab)->first();
            return $userExterne ? $userExterne->nom_user_ext : 'N/A';
        }
        return 'N/A';
    }
    
    public function getPrenomAttribute(): string
    {
        if ($this->type_pers_lab === 'personnel' && $this->personnel) {
            return $this->personnel->prenom_pers;
        } elseif ($this->type_pers_lab === 'user' && $this->user) {
            return $this->user->prenom_user;
        } elseif ($this->type_pers_lab === 'user_externe') {
            $userExterne = \App\Models\laboratoires\UserExterne::where('id_user_ext', $this->id_pers_lab)->first();
            return $userExterne ? $userExterne->prenom_user_ext : '';
        }
        return '';
    }
    
    public function getEmailAttribute(): ?string
    {
        if ($this->type_pers_lab === 'personnel' && $this->personnel) {
            return $this->personnel->email_pers ?? null;
        } elseif ($this->type_pers_lab === 'user' && $this->user) {
            return $this->user->email ?? null;
        } elseif ($this->type_pers_lab === 'user_externe') {
            $userExterne = \App\Models\laboratoires\UserExterne::where('id_user_ext', $this->id_pers_lab)->first();
            return $userExterne ? $userExterne->email_user_ext : null;
        }
        return null;
    }
    
    public function getTelephoneAttribute(): ?string
    {
        if ($this->type_pers_lab === 'personnel' && $this->personnel) {
            return $this->personnel->tel_pers ?? null;
        } elseif ($this->type_pers_lab === 'user' && $this->user) {
            return $this->user->telephone ?? null;
        } elseif ($this->type_pers_lab === 'user_externe') {
            $userExterne = \App\Models\laboratoires\UserExterne::where('id_user_ext', $this->id_pers_lab)->first();
            return $userExterne ? $userExterne->tel_user_ext : null;
        }
        return null;
    }
    
    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
    
    public function scopeInactif($query)
    {
        return $query->where('statut', 'inactif');
    }
    
    public function scopeByType($query, $type)
    {
        return $query->where('type_pers_lab', $type);
    }
    
    // Méthodes utilitaires
    public function isActif()
    {
        return $this->statut === 'actif';
    }
    
    public function isInactif()
    {
        return $this->statut === 'inactif';
    }
    
    public function isPersonnel()
    {
        return $this->type_pers_lab === 'personnel';
    }
    
    public function isUser()
    {
        return $this->type_pers_lab === 'user';
    }
    
    public function isUserExterne()
    {
        return $this->type_pers_lab === 'user_externe';
    }
    
    /**
     * Obtenir le type formaté
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type_pers_lab) {
            'personnel' => 'Personnel',
            'user' => 'Utilisateur',
            'user_externe' => 'Utilisateur externe',
            default => ucfirst($this->type_pers_lab)
        };
    }
    
    /**
     * Obtenir le badge du statut
     */
    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'actif' => 'success',
            'inactif' => 'danger',
            default => 'secondary'
        };
    }
}