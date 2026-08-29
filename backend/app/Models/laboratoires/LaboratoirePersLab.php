<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaboratoirePersLab extends Model
{
    protected $table = 'laboratoire_pers_lab';

    // Assurez-vous que cette table a bien une colonne 'id' auto-incrémentée
    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'code_lab',
        'id_pers_lab',
        'id_user_externe',
        'id_rl',
        'date_affectation',
        'date_fin_affectation',
        'statut',
    ];

    protected $casts = [
        'date_affectation' => 'date',
        'date_fin_affectation' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function laboratoire(): BelongsTo
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }

    public function persLab(): BelongsTo
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }

    public function userExterne(): BelongsTo
    {
        return $this->belongsTo(UserExterne::class, 'id_user_externe', 'id_user_ext');
    }

    public function roleLabo(): BelongsTo
    {
        return $this->belongsTo(RoleLabo::class, 'id_rl', 'id_rl');
    }

    // Relations pour les réservations et entretiens
    public function reservations(): HasMany
    {
        return $this->hasMany(ReservationAgent::class, 'id_pers_lab', 'id');
    }

    public function entretiens(): HasMany
    {
        return $this->hasMany(EntretienReparation::class, 'id_pers_lab', 'id');
    }

    // Accesseurs
    public function getNomCompletAttribute(): string
    {
        if ($this->persLab) {
            return $this->persLab->nom_complet;
        } elseif ($this->userExterne) {
            return $this->userExterne->nom_user_ext.' '.$this->userExterne->prenom_user_ext;
        }

        return 'N/A';
    }

    public function getNomAttribute(): string
    {
        if ($this->persLab) {
            return $this->persLab->nom;
        } elseif ($this->userExterne) {
            return $this->userExterne->nom_user_ext;
        }

        return 'N/A';
    }

    public function getPrenomAttribute(): string
    {
        if ($this->persLab) {
            return $this->persLab->prenom;
        } elseif ($this->userExterne) {
            return $this->userExterne->prenom_user_ext;
        }

        return '';
    }

    public function getEmailAttribute(): ?string
    {
        if ($this->persLab) {
            return $this->persLab->email;
        } elseif ($this->userExterne) {
            return $this->userExterne->email_user_ext;
        }

        return null;
    }

    public function getTelephoneAttribute(): ?string
    {
        if ($this->persLab) {
            return $this->persLab->telephone;
        } elseif ($this->userExterne) {
            return $this->userExterne->tel_user_ext;
        }

        return null;
    }

    public function getTypeMembreAttribute(): string
    {
        if ($this->persLab) {
            return $this->persLab->type_pers_lab;
        } elseif ($this->userExterne) {
            return 'user_externe';
        }

        return 'inconnu';
    }

    public function getTypeMembreLabelAttribute(): string
    {
        $type = $this->type_membre;

        return match ($type) {
            'personnel' => 'Personnel',
            'user' => 'Utilisateur',
            'user_externe' => 'Utilisateur externe',
            default => ucfirst($type)
        };
    }

    public function getDateAffectationFormattedAttribute()
    {
        return $this->date_affectation ? $this->date_affectation->format('d/m/Y') : 'Non définie';
    }

    public function getDateFinAffectationFormattedAttribute()
    {
        return $this->date_fin_affectation ? $this->date_fin_affectation->format('d/m/Y') : 'Indéterminée';
    }

    public function getStatutBadgeAttribute()
    {
        return match ($this->statut) {
            'actif' => 'success',
            'inactif' => 'danger',
            'suspendu' => 'warning',
            'en_attente' => 'info',
            'rejeté' => 'dark',
            default => 'secondary'
        };
    }

    public function getStatutLabelAttribute()
    {
        return match ($this->statut) {
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu',
            'en_attente' => 'En attente',
            'rejeté' => 'Rejeté',
            default => ucfirst($this->statut)
        };
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

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeByLaboratoire($query, $code_lab)
    {
        return $query->where('code_lab', $code_lab);
    }

    public function scopeByRole($query, $id_rl)
    {
        return $query->where('id_rl', $id_rl);
    }

    public function scopeInternes($query)
    {
        return $query->whereNotNull('id_pers_lab')->whereNull('id_user_externe');
    }

    public function scopeExternes($query)
    {
        return $query->whereNotNull('id_user_externe')->whereNull('id_pers_lab');
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

    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function isExterne()
    {
        return $this->id_user_externe !== null;
    }

    public function isInterne()
    {
        return $this->id_pers_lab !== null && $this->id_user_externe === null;
    }

    /**
     * Vérifier si l'affectation est toujours valide
     */
    public function isValide()
    {
        if (! $this->isActif()) {
            return false;
        }

        if ($this->date_fin_affectation && $this->date_fin_affectation->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Obtenir le nombre de jours restants avant la fin de l'affectation
     */
    public function getJoursRestantsAttribute()
    {
        if (! $this->date_fin_affectation) {
            return null;
        }

        return now()->diffInDays($this->date_fin_affectation, false);
    }

    /**
     * Obtenir le nombre de réservations actives
     */
    public function getReservationsActivesCount()
    {
        return $this->reservations()
            ->where('statut', 'confirmé')
            ->where('debut_reserv', '<=', now())
            ->where('fin_reserv', '>=', now())
            ->count();
    }

    /**
     * Obtenir le nombre d'entretiens en cours
     */
    public function getEntretiensEnCoursCount()
    {
        return $this->entretiens()
            ->whereIn('statut_entretien', ['En cours', 'En pause'])
            ->count();
    }
}
