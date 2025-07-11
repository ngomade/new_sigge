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
    
    public function scopeEnUtilisation($query)
    {
        return $query->where('etat', 'en utilisation');
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
            'en utilisation' => 'info',
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
            'en utilisation' => 'En utilisation',
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
    
    public function isEnUtilisation()
    {
        return $this->etat === 'en utilisation';
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
            ->whereIn('statut_entretien', ['En cours', 'En pause'])
            ->first();
    }
    
    /**
     * Vérifier si l'équipement a des réservations futures confirmées
     */
    public function hasReservationsFutures()
    {
        return $this->reservations()
            ->where('statut', 'confirmé')
            ->where('debut_reserv', '>', now())
            ->exists();
    }
    
    /**
     * Obtenir les réservations futures confirmées
     */
    public function getReservationsFutures()
    {
        return $this->reservations()
            ->where('statut', 'confirmé')
            ->where('debut_reserv', '>', now())
            ->orderBy('debut_reserv')
            ->get();
    }
    
    /**
     * Vérifier s'il y a un conflit de réservation pour une période donnée
     */
    public function hasConflitReservation($debut, $fin, $excludeId = null)
    {
        $query = $this->reservations()
            ->where('statut', 'confirmé')
            ->where(function($q) use ($debut, $fin) {
                $q->whereBetween('debut_reserv', [$debut, $fin])
                  ->orWhereBetween('fin_reserv', [$debut, $fin])
                  ->orWhere(function($subQ) use ($debut, $fin) {
                      $subQ->where('debut_reserv', '<=', $debut)
                           ->where('fin_reserv', '>=', $fin);
                  });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
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
    
    /**
     * Obtenir le prochain entretien programmé
     */
    public function getProchainEntretien()
    {
        return $this->entretiens()
            ->where('statut_entretien', 'En cours')
            ->where('debut_entretien', '>', now())
            ->orderBy('debut_entretien')
            ->first();
    }
    
    /**
     * Calculer l'âge de l'équipement
     */
    public function getAgeAttribute()
    {
        if (!$this->date_achat) {
            return null;
        }
        
        return $this->date_achat->diffInYears(now());
    }
    
    /**
     * Obtenir l'âge formaté
     */
    public function getAgeFormattedAttribute()
    {
        $age = $this->age;
        if ($age === null) {
            return 'Âge inconnu';
        }
        
        if ($age === 0) {
            return 'Moins d\'un an';
        }
        
        return $age . ' an' . ($age > 1 ? 's' : '');
    }
}