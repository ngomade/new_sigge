<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntretienReparation extends Model
{
    protected $table = 'entretien_reparation';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'code_equip',
        'id_pers_lab',
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
        return $this->belongsTo(\App\Models\laboratoires\PersLab::class, 'id_pers_lab', 'id_pers_lab');
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

    public function scopeEntretien($query)
    {
        return $query->where('type_entretien', 'entretien');
    }

    public function scopeReparation($query)
    {
        return $query->where('type_entretien', 'reparation');
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

    public function getCoutFormattedAttribute()
    {
        return $this->cout ? number_format($this->cout, 0, ',', ' ') . ' FCFA' : 'Non défini';
    }

    public function getDebutFormattedAttribute()
    {
        return $this->debut_entretien ? $this->debut_entretien->format('d/m/Y') : 'Non définie';
    }

    public function getFinFormattedAttribute()
    {
        return $this->fin_entretien ? $this->fin_entretien->format('d/m/Y') : 'Non définie';
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

    public function isEntretien()
    {
        return $this->type_entretien === 'entretien';
    }

    public function isReparation()
    {
        return $this->type_entretien === 'reparation';
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

        return $duree . ' jour(s)';
    }
}
