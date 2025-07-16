<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EntretienReparation extends Model
{
    protected $table = 'entretien_reparation';

    // Utiliser 'id' comme clé primaire
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'code_equip',
        'id_pers_lab',
        'id_user_ext', // nouveau champ
        'statut_entretien',
        'debut_entretien',
        'fin_entretien',
        'type_entretien',
        'desc_entretien',
        'cout'
    ];

    protected $casts = [
        'debut_entretien' => 'date',
        'fin_entretien' => 'date',
        'cout' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relations
    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipements::class, 'code_equip', 'code_equip');
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(LaboratoirePersLab::class, 'id_pers_lab', 'id');
    }

    public function userExterne(): BelongsTo
    {
        return $this->belongsTo(UserExterne::class, 'id_user_ext', 'id_user_ext');
    }

    // Scopes
    public function scopeEnCours($query)
    {
        return $query->where('statut_entretien', 'En cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('statut_entretien', 'Terminé');
    }

    public function scopeEnPause($query)
    {
        return $query->where('statut_entretien', 'En pause');
    }

    public function scopeAnnule($query)
    {
        return $query->where('statut_entretien', 'Annulé');
    }

    // Accesseurs
    public function getStatutBadgeAttribute()
    {
        return match($this->statut_entretien) {
            'En cours' => 'warning',
            'Terminé' => 'success',
            'En pause' => 'info',
            'Annulé' => 'danger',
            default => 'secondary'
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match($this->type_entretien) {
            'entretien' => 'primary',
            'reparation' => 'danger',
            default => 'secondary'
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type_entretien) {
            'entretien' => 'Entretien',
            'reparation' => 'Réparation',
            default => ucfirst($this->type_entretien)
        };
    }

    public function getDebutFormattedAttribute()
    {
        return $this->debut_entretien ? $this->debut_entretien->format('d/m/Y') : 'Non définie';
    }

    public function getFinFormattedAttribute()
    {
        return $this->fin_entretien ? $this->fin_entretien->format('d/m/Y') : 'Non définie';
    }

    public function getCoutFormattedAttribute()
    {
        if ($this->cout === null || $this->cout == 0) {
            return 'Non défini';
        }
        return number_format($this->cout, 0, ',', ' ') . ' FCFA';
    }

    // Méthodes
    public function isEnCours()
    {
        return $this->statut_entretien === 'En cours';
    }

    public function isTermine()
    {
        return $this->statut_entretien === 'Terminé';
    }

    public function isEnPause()
    {
        return $this->statut_entretien === 'En pause';
    }

    public function isAnnule()
    {
        return $this->statut_entretien === 'Annulé';
    }

    public function getDuree()
    {
        if (!$this->debut_entretien || !$this->fin_entretien) {
            return null;
        }

        return $this->debut_entretien->diffInDays($this->fin_entretien);
    }

    public function getDureeFormatted()
    {
        $duree = $this->getDuree();
        if ($duree === null) {
            return 'Non définie';
        }

        if ($duree === 0) {
            return '1 jour';
        }

        return ($duree + 1) . ' jour' . ($duree > 0 ? 's' : '');
    }

    public function getJoursRestants()
    {
        if (!$this->isEnCours() || !$this->fin_entretien) {
            return null;
        }

        return now()->diffInDays($this->fin_entretien, false);
    }

    public function getJoursRestantsFormatted()
    {
        $jours = $this->getJoursRestants();
        if ($jours === null) {
            return '';
        }

        if ($jours === 0) {
            return 'Se termine aujourd\'hui';
        } elseif ($jours < 0) {
            return 'En retard de ' . abs($jours) . ' jour' . (abs($jours) > 1 ? 's' : '');
        }

        return $jours . ' jour' . ($jours > 1 ? 's' : '') . ' restant' . ($jours > 1 ? 's' : '');
    }
}
