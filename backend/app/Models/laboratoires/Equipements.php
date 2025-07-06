<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Equipements extends Model
{
    protected $table = 'equipements';
    protected $primaryKey = 'code_equip';
    public $incrementing = true;
    protected $fillable = [
        'nom_equip',
        'ref_equip',
        'desc_equip',
        'etat',
        'date_achat',
        'valeur',
        'localisation',
        'code_lab'
    ];

    protected $casts = [
        'date_achat' => 'date',
        'valeur' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relations
    public function laboratoire(): BelongsTo
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }

    public function entretiens(): HasMany
    {
        return $this->hasMany(EntretienReparation::class, 'code_equip', 'code_equip');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ReservationAgent::class, 'code_equip', 'code_equip');
    }

    // Scopes
    public function scopeDisponible($query)
    {
        return $query->where('etat', 'disponible');
    }

    public function scopeEnMaintenance($query)
    {
        return $query->where('etat', 'en maintenance');
    }

    public function scopeHorsService($query)
    {
        return $query->where('etat', 'hors service');
    }

    public function scopeByLaboratoire($query, $code_lab)
    {
        return $query->where('code_lab', $code_lab);
    }

    // Accesseurs
    public function getEtatBadgeAttribute()
    {
        return match($this->etat) {
            'disponible' => 'success',
            'en maintenance' => 'warning',
            'hors service' => 'danger',
            'réservé' => 'info',
            default => 'secondary'
        };
    }

    public function getEtatLabelAttribute()
    {
        return match($this->etat) {
            'disponible' => 'Disponible',
            'en maintenance' => 'En maintenance',
            'hors service' => 'Hors service',
            'réservé' => 'Réservé',
            default => ucfirst($this->etat)
        };
    }

    public function getValeurFormattedAttribute()
    {
        return $this->valeur ? number_format($this->valeur, 0, ',', ' ') . ' FCFA' : 'Non définie';
    }

    public function getDateAchatFormattedAttribute()
    {
        return $this->date_achat ? $this->date_achat->format('d/m/Y') : 'Non définie';
    }

    // Méthodes
    public function isDisponible()
    {
        return $this->etat === 'disponible';
    }

    public function isEnMaintenance()
    {
        return $this->etat === 'en maintenance';
    }

    public function isHorsService()
    {
        return $this->etat === 'hors service';
    }

    public function hasReservationActive()
    {
        return $this->reservations()
            ->where('statut', 'confirmé')
            ->where('debut_reserv', '<=', now())
            ->where('fin_reserv', '>=', now())
            ->exists();
    }

    public function getReservationActive()
    {
        return $this->reservations()
            ->where('statut', 'confirmé')
            ->where('debut_reserv', '<=', now())
            ->where('fin_reserv', '>=', now())
            ->first();
    }

    public function getEntretienEnCours()
    {
        return $this->entretiens()
            ->where('statut_entretien', 'En cours')
            ->first();
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
